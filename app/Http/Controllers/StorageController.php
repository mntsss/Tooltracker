<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StorageRequest;
use App\Storage;
class StorageController extends Controller
{

    public function list()
    {
      return response()->json(Storage::active()->with('managers')->get());
    }

    public function get(int $id)
    {
      $storage = Storage::with('groups')->find($id);
      return response()->json($storage);
    }

    public function create(StorageRequest $request)
    {
      $storage = new Storage();
      $storage->fill($request->all())->save();

      return response()->json(["message" => "Atlikta!", "success" => "Sandėlis sėkmingai pridėtas."], 200);
    }

    public function edit(StorageRequest $request)
    {
      Storage::find($request->id)->rename($request->name);

      return response()->json(["message" => "Atlikta!", "success" => "Sandėlis pervadintas."], 200);
    }

    public function delete(Storage $storage)
    {
      /**
      * Check if storage can be deleted...
      */
      $storage->delete();

      return response()->json(["message" => "Atlikta!", "success" => "Sandėlis sėkmingai ištrintas."], 200);
    }
}
