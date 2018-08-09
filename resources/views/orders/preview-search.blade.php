@extends('layouts.main')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-10 col-12">
        <div class="card bg-dark text-light">
          <div class="card-header">
            @if($order->make != "" && $order->model != "")
              {{$order->make}}&#160;{{$order->model}}&#160;{{$order->year}}
            @else
              {{$order->make}}&#160;{{$order->name}}
            @endif
          </div>
          <div class="card-body">
            @if($order->model != "")
              <div class="text-left">
                {{$order->name}}
              </div>
            @endif
            @if($order->year != "")
              <div class="text-left">
                <b>Metai: </b>{{$order->year}}
              </div>
            @endif
            @if($order->fuel != "")
              <div class="text-left">
                <b>Kuras: </b>{{$order->fuel}}
              </div>
            @endif
            @if($order->power != "0")
              <div class="text-left">
                <b>Galia: </b>{{$order->power}}
              </div>
            @endif
            @if($order->chassisNr != "")
              <div class="text-left">
                <b>Kėbulas: </b>{{$order->chassisNr}}
              </div>
            @endif
            @if($order->carType != "")
              <div class="text-left">
                <b>Kėbulo tipas: </b>{{$order->carType}}
              </div>
            @endif
            @if($order->vin != "")
              <div class="text-left">
                <b>VIN: </b>{{$order->vin}}
              </div>
            @endif
            <form method="post" action="{{route('search.add.another')}}">
              {{ csrf_field() }}
              <input type="hidden" name="make" value="{{$order->make}}" />
              <input type="hidden" name="model" value="{{$order->model}}" />
              <input type="hidden" name="year" value="{{$order->year}}" />
              <input type="hidden" name="fuel" value="{{$order->fuel}}" />
              <input type="hidden" name="vin" value="{{$order->vin}}" />
              <input type="hidden" name="power" value="{{$order->power}}" />
              <input type="hidden" name="cartype" value="{{$order->carType}}" />
              <input type="hidden" name="carchassis" value="{{$order->chassisNr}}" />
              <button type="submit" class="btn btn-outline-success" style="border-radius: 0px !important"><span class="fa fa-plus"></span>&#160;Pridėti kitą detalę paieškai</button><br />
            </form>

            @if($order->code != "")
              <div class="text-left">
                <b>Detalės kodas: </b>{{$order->code}}
              </div>
            @endif
            @if($order->description != "")
              <div class="text-left">
                <b>Aprašymas: </b>{{$order->description}}
              </div>
            @endif
            @if($order->image != "")
                <div class="col-md remove-all-margin" style="max-width: 350px; max-height: 350px; text-align:center; padding: 10px !important">
                  <a href="{{asset("media/uploads/".$order->image)}}"><img src="{{asset("media/uploads/".$order->image)}}" alt="img" style="max-width: 340px; height: auto"/></a>
                </div>
            @endif
            @if($order->timeLimit != "")
              <div class="text-left">
                <b>Pristatyti iki: <br /><u>{{$order->timeLimit}}</u></b>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
