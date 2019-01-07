<?php
namespace App\Http\Services;

use App\Action;
use App\Item;
use Auth;

class ActionRecorderService
{
  public function record(Item $item, string $new_status, $user = null, $object = null)
  {
    $action = $this->createAction($item);
    $action->user_id = $this->checkUser($user);
    $action->object_id = $object;
    $action->current_status = $new_status;
    $action->save();
  }

  private function checkUser($user_id):int
  {
    if(!$user_id)
      return Auth::user()->id;
    return $user_id;
  }

  private function createAction(Item $item):Action
  {
    $action = new Action();
    $action->item_id = $item->id;
    $action->storage_id = $item->storage_id;
    $action->previous_status = $item->status;
    return $action;
  }
}
