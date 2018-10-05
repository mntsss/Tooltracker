@extends('layouts.main')

@section('content')
  <script>
  function orderreturn(nr, name)
  {
  $('#deliveredLink').attr('href', function() {
        return '/order/return/' + nr;
    });
    $('#orderDelivered .modal-body').html('<p>Gražinti užsakymą "'+ name +'"?</p>')
    $('#orderDelivered').modal();
  }
  </script>
  @include('comfirmations.order-delivered')
<div class="container">
  <div class="row mx-0 justify-content-center">
    <div class="col-10">
      @component('include.filter-form')
            {{route('delivered')}}
          @endcomponent
    </div>
  </div>
    <div class="row mx-0 justify-content-center">
      @if (session('status'))
          <div class="alert alert-success">
            {{ session('status') }}
          </div>
      @endif
        <div class="col-md-10">
          <div class="card">
            <div class="card-header">
              <div class="row mx-0 text-center text-dark">
                <h3 class="mr-auto">Pristatyti užsakymai</h3>
              </div>
            </div>
            <div class="card-body remove-all-padding">
              @if(count($param['orders']) < 1)
                <h5>Pristatytų užsakymų nėra</h5>
              @else
                @foreach ($param['orders'] as $order)
                  <div class="row mx-0 remove-side-margin">
                    <div class="col-8 fit-text-dots order-entry" onclick="window.location='{{route('order.view', ['id' => $order->id])}}';">
                      @if($order->timeLimit != "")<span class="far fa-clock"></span>@endif
                      @if($order->make != ""){{$order->make}}@endif
                      @if($order->model != ""){{$order->model}}@endif
                      @if($order->chassisNr != ""){{$order->chassisNr}}@endif
                      @if($order->year != ""){{$order->year}}@endif
                        {{$order->name}}
                    </div>
                    <div class="row mx-0 col-sm-4" style="font-size: 18px; line-height: 2.5">
                      <div class="col-10 text-right">
                        {{$order->updated_at}}
                      </div>
                      <div class="col-2 text-center btn-functions">
                        <span class="fas fa-angle-double-down text-dark "></span>
                       <div class="functions-box" style="border: 1px solid black; background: #D9D9D9 !important; border-radius: 3px; padding: 5px !important">
                         <div class="btn-group-vertical">
                           <button type="button" class="btn btn-warning btn-orders-function" onclick="orderreturn('{{$order->id}}','{{htmlspecialchars($order->name, ENT_QUOTES)}}')"><span class="fas fa-reply"></span>&#160;Grąžinti</button>
                         </div>
                       </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              @endif
              <div class="col-12 text-center center-content" style="margin-top: 15px !important">
                {{$param['orders']->appends(['query' => $param['lastSearch']['query'], 'from' => $param['lastSearch']['from'], 'til' => $param['lastSearch']['til']])->links()}}
              </div>
            </div>
          </div>

        </div>
    </div>
</div>
@endsection
