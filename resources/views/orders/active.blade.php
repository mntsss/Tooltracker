@extends('layouts.main')

@section('content')
@include('comfirmations.order-delete')
@include('comfirmations.order-delivered')
<script>
setTimeout(function(){
    location = ''
  },180000);
function orderdelete(nr, name)
{
$('#deleteLink').attr('href', function() {
      return '/order/delete/' + nr;
  });
$('#orderDelete .modal-body').html('<p>Atšaukti užsakymą "'+ name +'"?</p>')
  $('#orderDelete').modal();
}
function orderdelivered(nr, name)
{
$('#deliveredLink').attr('href', function() {
      return '/order/delivered/' + nr;
  });
  $('#orderDelivered .modal-body').html('<p>Ar tikrai užsakymas "'+ name +'" pristatytas?</p>')
  $('#orderDelivered').modal();
}
function orderreturned(nr, name)
{
$('#deliveredLink').attr('href', function() {
      return '/order/returned/' + nr;
  });
  $('#orderDelivered .modal-body').html('<p>Užsakymas "'+ name +'" grąžintas?</p>')
  $('#orderDelivered').modal();
}
</script>
<div class="container">
    <div class="row justify-content-center">
      @if (session('status'))
          <div class="alert alert-success">
            {{ session('status') }}
          </div>
      @endif
        <div class="col-md-10">
          <div class="card">
            <div class="card-header">
              <div class="row text-center text-dark">
                <h3 class="mr-auto">Aktyvūs užsakymai</h3>
                @if(Auth::user()->role == "Vadybininkas")
                <a href="{{route('order.add')}}" class="btn btn-dark"><span class="fas fa-car text-white">Atvežti</span></a>&#160;
                <a href="{{ route('search.add')}}" class="btn btn-dark"><span class="fas fa-search text-white">Surasti</span></a>
              @endif
              </div>
            </div>
            <div class="card-body remove-all-padding">
              @if(count($param['orders']) < 1)
                <h6>Aktyvių užsakymų nėra</h6>
              @else
                @foreach ($param['orders'] as $order)
                  <div class="row remove-side-margin">
                    <div class="col-sm-11 col-10 fit-text-dots order-entry" onclick="window.location='{{route('order.view', ['id' => $order->id])}}';">
                      @if($order->important && $order->status == "active")<span class="fas fa-exclamation text-danger"></span>&#160;@endif
                      @if($order->timeLimit != "" && $order->status == "active")<span class="far fa-clock"></span>
                      @elseif ($order->status == "return")<span class="fas fa-reply"></span>
                      @elseif ($order->status == "found")<span class="fas fa-car"></span>@endif
                      @if($order->make != ""){{$order->make}}@endif
                      @if($order->model != ""){{$order->model}}@endif
                      @if($order->chassisNr != ""){{$order->chassisNr}}@endif
                      @if($order->year != ""){{$order->year}}@endif
                        {{$order->name}}
                    </div>
                    <div class="col-sm-1 col-2 text-center btn-functions">
                       <span class="fas fa-angle-double-down text-dark"></span>
                       <div class="functions-box">
                         <div class="btn-group-vertical">
                           @if (Auth::user()->role == "Vadybininkas")
                              @if($order->status == "active" || $order->status == "found")<button type="button" class="btn btn-success btn-orders-function" onclick="orderdelivered('{{$order->id}}', '{{htmlspecialchars($order->name, ENT_QUOTES)}}')"><span class="far fa-check-circle"></span>&#160;Pristatyta</button>@endif
                              @if($order->status == "return")<button type="button" class="btn btn-success btn-orders-function" onclick="orderreturned('{{$order->id}}', '{{htmlspecialchars($order->name, ENT_QUOTES)}}')"><span class="far fa-check-circle"></span>&#160;Grąžinta</button>@endif
                              @if($order->status != "found")<button type="button" class="btn btn-danger btn-orders-function" onclick="orderdelete('{{$order->id}}', '{{htmlspecialchars($order->name, ENT_QUOTES)}}')"><span class="far fa-times-circle"></span>&#160;Atšaukti</button>@endif
                           @elseif (Auth::user()->role == "Tiekėjas")
                             @if($order->status == "active")<a href="{{route('order.found', ['id' => $order->id])}}" class="btn btn-success btn-orders-function"><span class="far fa-check-circle"></span>&#160;Gavau</a>@endif
                             @if($order->status == "return")<button type="button" class="btn btn-success btn-orders-function" onclick="orderreturned('{{$order->id}}', '{{htmlspecialchars($order->name, ENT_QUOTES)}}')"><span class="far fa-check-circle"></span>&#160;Grąžinau</button>@endif
                           @endif
                         </div>
                       </div>
                    </div>
                  </div>
                @endforeach
              @endif
              <div class="col-12 text-center center-content" style="margin-top: 15px !important">
                {{$param['orders']->links()}}
              </div>

            </div>
          </div>

        </div>
    </div>
</div>
@endsection
