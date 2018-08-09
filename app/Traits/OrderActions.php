<?php
namespace App\Traits;
use App\Order;
use App\Action;
trait OrderActions{

  public function Print(Action $action){
    $order = Order::find($action->orderId);

    $output = [
      'active' => [
        'found' => $action->user." gavo <span style='text-decoration: underline'>".$order->make." ".$order->model." ".$order->year." ".$order->name."</span>",
        'deleted' => $action->user." atšaukė užsakymą <span style='text-decoration: underline'>".$order->make." ".$order->model." ".$order->year." ".$order->name."</span>",
        'delivered' => "<span style='text-decoration: underline'>".$order->make." ".$order->model." ".$order->year." ".$order->name."</span> pristatytas vadybininkui ".$action->user,
      ],
      'delivered' => [
        'return' => $action->user.' pažymėjo grąžinimui <span style="text-decoration: underline">'.$order->make." ".$order->model." ".$order->year." ".$order->name."</span>",
      ],
      'return' => [
        'returned' => $action->user.' grąžino <span style="text-decoration: underline">'.$order->make." ".$order->model." ".$order->year." ".$order->name."</span>",
        'deleted' =>$action->user." atšaukė gražinimą <span style='text-decoration: underline'>".$order->make." ".$order->model." ".$order->year." ".$order->name."</span>",
      ],
      'found' => [
        'delivered' => "<span style='text-decoration: underline'>".$order->make." ".$order->model." ".$order->year." ".$order->name."</span> pristatytas vadybininkui ".$action->user."</ul>",
      ],
      'deleted' => [
        'active' => $action->user." atnaujino užsakymą <span style='text-decoration: underline'>". $order->make." ".$order->model." ".$order->year." ".$order->name."</span>",
      ],
      '-' => [
        'active' => $action->user." pridėjo užsakymą <span style='text-decoration: underline'>". $order->make." ".$order->model." ".$order->year." ".$order->name."</span>",
      ]
    ];
    return preg_replace('/\s+/', ' ',$output[$action->oldStatus][$action->newStatus]);
  }
}
