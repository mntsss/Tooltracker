@extends('layouts.main')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-10">
      @component('include.filter-form')
            {{route('returned')}}
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
                <h3 class="mr-auto">Gražinti užsakymai</h3>
              </div>
            </div>
            <div class="card-body remove-all-padding">
              @if(count($param['orders']) < 1)
                <h5>Grąžintų užsakymų nėra</h5>
              @else
                @foreach ($param['orders'] as $order)
                  <div class="row remove-side-margin">
                    <div class="col-8 fit-text-dots order-entry" onclick="window.location='{{route('order.view', ['id' => $order->id])}}';">
                      @if($order->make != ""){{$order->make}}@endif
                      @if($order->model != ""){{$order->model}}@endif
                      @if($order->chassisNr != ""){{$order->chassisNr}}@endif
                      @if($order->year != ""){{$order->year}}@endif
                        {{$order->name}}
                    </div>
                    <div class="col-4 text-right">
                      {{$order->updated_at}}
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
