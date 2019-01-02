<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddItemChipRequest;
use App\Http\Requests\FindItemWithCodeRequest;
use App\Http\Requests\Item\ItemRequest;
use App\Http\Requests\ItemSearchRequest;
use App\Http\Services\ImageService;
use App\Item;
use App\ItemGroup;
use App\ItemImage;
use App\Jobs\Item\CreateItemJob;
use App\Code;
use App\Traits\ActionHistory;
use App\Traits\ItemInfo;
use Auth;
use App\Http\Services\ActionRecorderService;

class ItemController extends Controller
{
    use ItemInfo;
    use ActionHistory;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function items(ItemGroup $groupID)
    {
        return response()->json($groupID->items);
    }

    // returns suspended item list
    public function suspendedItems()
    {
        return response()->json(Item::suspended()->get());
    }

    public function deletedItems()
    {
        return response()->json(Item::deleted()->get());
    }

    public function history($id)
    {
        $item = Item::with(['withdrawals', 'reservations'])->find($id);
        $withdrawals = $this->createWithdrawalHistory($item);
        $reservations = $this->createReservationHistory($item);
        $suspentions = $this->createSuspentionHistory($item);

        $sorted = $withdrawals->merge($reservations)->merge($suspentions)->sortByDesc('Date')->values()->all();

        return response()->json(['actions' => $sorted, 'item' => $item], 200);
    }

    public function create(ItemRequest $request, ImageService $imageService, ActionRecorderService $recorderService)
    {
        $item = new Item();
        $item->fill($request->item())->save();
        $recorderService->record($item, Item::ITEM_IN_STORAGE);
        $item->status = Item::ITEM_IN_STORAGE;
        $item->save();

        if($request->hasImage()){
            $name = $imageService->save($request->image());
            $image = new ItemImage();
            $image->item_id = $item->id;
            $image->name = $name;
            $image->save();
        }
        if($request->hasCode()){
            $code = new Code();
            $code->code = $request->code();
            $code->item_id = $item->id;
            $code->save();
        }

        return response()->json(['message' => 'Atlikta!', 'success' => 'Įrankis pridėtas į grupę.'], 200);
    }

    public function edit(ItemRequest $request)
    {
        $item = Item::find($request->id);

        if ($item) {
            if ($request->has('name'))
                $item->ItemName = $request->name;
            if ($request->has('warranty_date'))
                $item->ItemWarranty = $request->warranty_date;
            if ($request->has('purchase_date'))
                $item->ItemPurchase = $request->purchase_date;
            if ($request->has('note'))
                $item->ItemNote = $request->note;
            if ($request->has('idnumber'))
                $item->ItemIdNumber = $request->idnumber;
            if ($request->has('acquired'))
                $item->ItemAcquiredFrom = $request->acquired;

            $item->save();
            return response()->json(['message' => 'Atlikta', 'success' => 'Įrankio informacija sėkmingai redaguota.']);
        } else {
            return response()->json(['message' => 'Klaida', 'errors' => ['name' => ['Įrankio nepavyko identifikuoti, arba įvyko klaida jungiantis į duomenų bazę. Susisiekite su administratoriumi.']]], 422);
        }
    }

    // returns item and its state by provided ID
    public function get($id)
    {
        $item = Item::with('images')->find($id);
        return response()->json($item, 200);
    }

    // restore item marked as deleted to selected item group

    public function restore(ItemRequest $request)
    {
        if (Item::find($request->id)->update(['ItemDeleted' => false, 'ItemGroupID' => $request->groupID]))
            return response()->json(['message' => 'Atlikta!', 'success' => 'Įrankis sėkmingai atkurtas į pasirinktą grupę.'], 200);
        else
            return response()->json(['message' => 'Klaida', 'errors' => ['name' => ['Įvyko klaida jungiantis į duomenų bazę. Susisiekite su administratoriumi.']]], 422);
    }

    // marks item as deleted
    public function delete(ItemRequest $request, ActionRecorderService $actionRecorder)
    {
        $item = Item::find($request->id);

        if ($item->status != Item::ITEM_IN_STORAGE)
            return response()->json(['message' => 'Klaida', 'errors' => ['name' => ['Įrankis negrąžintas į sandėlį, todėl negali būti ištrintas.']]], 422);

        $actionRecorder->record($item, Item::ITEM_DELETED);

        if($item->update(['ItemDeleted' => true]))
            return response()->json(['message' => 'Atlikta!', 'success' => 'Įrankis sėkmingai ištrintas.'], 200);
        else return response()->json(['message' => 'Klaida', 'errors' => ['name' => ['Kažkur įvyko klaida siunčiant užklausą į duomenų bazę. Susisiekite su administracija.']]], 422);
    }

    // add new RFID chip for item
    public function addchip(AddItemChipRequest $request)
    {
        if (Code::create(['Code' => $request->code, 'ItemID' => $request->id]))
            return response()->json(['message' => 'Atlikta', 'success' => 'Naujas čipas buvo sėkmingai priskirtas įrankiui!'], 200);
        else return response()->json(['message' => 'Klaida', 'errors' => ['name' => ['Kažkur įvyko klaida siunčiant užklausą į duomenų bazę. Susisiekite su administracija.']]], 422);
    }

    // finds item which is assigned to provided RFID code
    public function findWithCode(FindItemWithCodeRequest $request)
    {
        $itemID = Code::where('Code', $request->code)->first()->ItemID;
        $item = Item::existing()->with(['lastWithdrawal', 'lastSuspention', 'lastReservation', 'images', 'itemGroup'])->find($itemID);
        $item->state = $this->getItemState($item);
        return response()->json($item, 200);
    }

    public function search(ItemSearchRequest $request)
    {
        $items = Item::where('ItemName', 'like', '%' . $request['query'] . '%')->orWhere('ItemIdNumber', 'like', $request['query'] . '%')->existing()->with(['lastWithdrawal', 'lastSuspention', 'lastReservation', 'images', 'itemGroup'])->limit(10)->get();

        foreach ($items as $item) {
            $item->state = $this->GetItemState($item);
        }
        return response()->json($items, 200);
    }

}
