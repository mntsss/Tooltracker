@extends('layouts.main')

@section('content')
  <script>
  function orderrefresh(nr, name)
  {
  $('#deliveredLink').attr('href', function() {
        return '/order/refresh/' + nr;
    });
    $('#orderDelivered .modal-body').html('<p>Atnaujinti užsakymą "'+ name +'"?</p>')
    $('#orderDelivered').modal();
  }
  </script>
  @include('comfirmations.order-delivered')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-10">
      @component('include.filter-form')
            {{route('deleted')}}
          @endcomponent
    </div>
  </div>
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
                <h3 class="mr-auto">Atšaukti užsakymai</h3>
              </div>
            </div>
            <div class="card-body remove-all-padding">
              @if(count($param['orders']) < 1)
                <h5>Atšauktų užsakymų nėra</h5>
              @else
                @foreach ($param['orders'] as $order)
                  <div class="row remove-side-margin">
                    <div class="col-sm-8 fit-text-dots order-entry" onclick="window.location='{{route('order.view', ['id' => $order->id])}}';">
                      @if($order->make != ""){{$order->make}}@endif
                      @if($order->model != ""){{$order->model}}@endif
                      @if($order->chassisNr != ""){{$order->chassisNr}}@endif
                      @if($order->year != ""){{$order->year}}@endif
                        {{$order->name}}
                    </div>
                    <div class="row col-sm-4" style="font-size: 18px; line-height: 2.5">
                      <div class="col-10 text-right">
                        {{$order->updated_at}}
                      </div>
                      <div class="col-2 text-center btn-functions">
                        <span class="fas fa-angle-double-down text-dark "></span>
                        <div class="functions-box" style="border: 1px solid black; background: #D9D9D9 !important; border-radius: 3px; padding: 5px !important">
                          <div class="btn-group-vertical">
                            <button type="button" class="btn btn-success btn-orders-function" onclick="orderrefresh('{{$order->id}}','{{htmlspecialchars($order->name, ENT_QUOTES)}}')"><span class="fas fa-redo"></span>&#160;Atnaujinti</button>
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
