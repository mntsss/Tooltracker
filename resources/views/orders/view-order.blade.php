@extends('layouts.main')

@section('content')
  @include('comfirmations.order-delete')
  @include('comfirmations.order-delivered')
  <script>
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
  function orderfound(nr, name)
  {
  $('#deliveredLink').attr('href', function() {
        return '/order/found/' + nr;
    });
    $('#orderDelivered .modal-body').html('<p>Užsakymas "'+ name +'" gautas?</p>')
    $('#orderDelivered').modal();
  }
  function orderrefresh(nr, name)
  {
  $('#deliveredLink').attr('href', function() {
        return '/order/refresh/' + nr;
    });
    $('#orderDelivered .modal-body').html('<p>Atnaujinti užsakymą "'+ name +'"?</p>')
    $('#orderDelivered').modal();
  }
  function orderreturn(nr, name)
  {
  $('#deliveredLink').attr('href', function() {
        return '/order/return/' + nr;
    });
    $('#orderDelivered .modal-body').html('<p>Gražinti užsakymą "'+ name +'"?</p>')
    $('#orderDelivered').modal();
  }
  </script>
  <div class="container">
    <div class="row">
      <div class="col-md-10 col-12">
        <div class="card bg-dark text-light">
          <div class="card-header">
            @if($param['order']->status == "return")
              Gražinimas:&#160;
            @endif
            @if($param['order']->make != "" && $param['order']->model != "")
              {{$param['order']->make}}&#160;{{$param['order']->model}}&#160;{{$param['order']->year}}
            @else
              {{$param['order']->make}}&#160;{{$param['order']->name}}
            @endif
            @if($param['order']->type == "search" && Auth::user()->role == "Vadybininkas")
              <form method="post" action="{{route('search.add.another')}}" class="flex-row-reverse">
                {{ csrf_field() }}
                <input type="hidden" name="make" value="{{$param['order']->make}}" />
                <input type="hidden" name="model" value="{{$param['order']->model}}" />
                <input type="hidden" name="year" value="{{$param['order']->year}}" />
                <input type="hidden" name="fuel" value="{{$param['order']->fuel}}" />
                <input type="hidden" name="vin" value="{{$param['order']->vin}}" />
                <input type="hidden" name="power" value="{{$param['order']->power}}" />
                <input type="hidden" name="cartype" value="{{$param['order']->carType}}" />
                <input type="hidden" name="carchassis" value="{{$param['order']->chassisNr}}" />
                <button type="submit" class="btn btn-outline-success" style="border-radius: 0px !important"><span class="fa fa-plus"></span>&#160;Kita detalė</button><br />
              </form>
            @endif
          </div>
          <div class="card-body">
            @if($param['order']->model != "")
              <div class="text-left">
                {{$param['order']->name}}
              </div>
            @endif
            @if($param['order']->timeLimit != "" && $param['order']->status != "return")
              <div class="text-left">
                <b>Pristatyti iki: <br /><u class="text-danger">{{$param['order']->timeLimit}}</u></b>
              </div>
            @endif
            @if($param['order']->year != "")
              <div class="text-left">
                <b>Metai: </b>{{$param['order']->year}}
              </div>
            @endif
            @if($param['order']->carType != "")
              <div class="text-left">
                <b>Kėbulo tipas: </b>{{$param['order']->carType}}
              </div>
            @endif
            @if($param['order']->chassisNr != "")
              <div class="text-left">
                <b>Kėbulas: </b>{{$param['order']->chassisNr}}
              </div>
            @endif
            @if($param['order']->power != "" && $param['order']->power != "0")
              <div class="text-left">
                <b>Galia: </b>{{$param['order']->power}} kw
              </div>
            @endif
            @if($param['order']->fuel != "")
              <div class="text-left">
                <b>Kuras: </b>{{$param['order']->fuel}}
              </div>
            @endif
            @if($param['order']->vin != "")
              <div class="text-left">
                <b>VIN: </b>{{$param['order']->vin}}
              </div>
            @endif
            @if($param['order']->code != "")
              <div class="text-left">
                <b>Detalės kodas: </b>{{$param['order']->code}}
              </div>
            @endif
            @if($param['order']->description != "")
              <div class="text-left">
                <b>Aprašymas: </b>{{$param['order']->description}}
              </div>
            @endif
            @if($param['order']->image != "")
                <div class="col-md-6 remove-all-margin remove-all-padding" style="text-align:center; padding: 10px">
                  <a href="{{asset("media/uploads/".$param['order']->image)}}"><img src="{{asset("media/uploads/".$param['order']->image)}}" alt="img" style="width: 100%; height: auto"/></a>
                </div>

            @elseif(Auth::user()->role == "Vadybininkas" && $param['order']->status == "active")
                <div class="text-left">
                  <b>Įkelti nuotrauką</b>
                </div>
                <form class="form-vertical" method="post" action="{{route('order.updatephoto.submit')}}" enctype="multipart/form-data" style="margin: 15px 0 !important">
                <div class="form-group row">
                  @csrf
                  <div class="col-9 text-left">
                       <input type="hidden" name="id" value="{{$param['order']->id}}" />
                       <input name="image" type="file" id="image" class="form-control{{ $errors->has('image') ? ' is-invalid' : '' }}" accept="image/*" placeholder="Pridėti nuotrauką" capture required>
                       @if ($errors->has('image'))
                           <span class="invalid-feedback">
                               <strong>{{ $errors->first('image') }}</strong>
                           </span>
                       @endif
                  </div>
                  <div class="col-3">
                    <button type="submit" class="btn btn-outline-warning"><span class="fas fa-upload"></span></button>
                  </div>
                </div>
              </form>
            @endif
            @if($param['order']->address != "")
              <div class="text-left">
                <h4>Kontaktai</h4>
                <a href="geo:0,0?q={{$param['order']->address}}" class="text-light">{{$param['order']->address}}</a><br />
                @if($param['order']->phone != "")
                  <a href="tel:{{$param['order']->phone}}" class="text-light">{{$param['order']->phone}}</a>
                @endif
              </div>
            @endif
            <div class="justify-content-center" style="padding: 10px;">
                @if (Auth::user()->role == "Vadybininkas")
                   @if($param['order']->status == "active" || $param['order']->status == "found")<button type="button" class="btn btn-success btn-orders-function" onclick="orderdelivered('{{$param['order']->id}}', '{{htmlspecialchars($param['order']->name, ENT_QUOTES)}}')"><span class="far fa-check-circle"></span>&#160;Pristatyta</button>@endif
                   @if($param['order']->status == "return")<button type="button" class="btn btn-success btn-orders-function" onclick="orderreturned('{{$param['order']->id}}', '{{htmlspecialchars($param['order']->name, ENT_QUOTES)}}')"><span class="far fa-check-circle"></span>&#160;Grąžinta</button>@endif
                   @if($param['order']->status == "active" || $param['order']->status == "return")<button type="button" class="btn btn-danger btn-orders-function" onclick="orderdelete('{{$param['order']->id}}', '{{htmlspecialchars($param['order']->name, ENT_QUOTES)}}')"><span class="far fa-times-circle"></span>&#160;Atšaukti</button>@endif
                   @if($param['order']->status == "deleted")<button type="button" class="btn btn-success btn-orders-function" onclick="orderrefresh('{{$param['order']->id}}','{{htmlspecialchars($param['order']->name, ENT_QUOTES)}}')"><span class="fas fa-redo"></span>&#160;Atnaujinti</button>@endif
                   @if($param['order']->status == "delivered")<button type="button" class="btn btn-warning btn-orders-function" onclick="orderreturn('{{$param['order']->id}}','{{htmlspecialchars($param['order']->name, ENT_QUOTES)}}')"><span class="fas fa-reply"></span>&#160;Grąžinti</button>@endif
                @elseif (Auth::user()->role == "Tiekėjas")
                  @if($param['order']->status == "active")<button type="button" class="btn btn-success btn-orders-function" onclick="orderfound('{{$param['order']->id}}', '{{htmlspecialchars($param['order']->name, ENT_QUOTES)}}')"><span class="far fa-check-circle"></span>&#160;Gavau</button>@endif
                  @if($param['order']->status == "return")<button type="button" class="btn btn-success btn-orders-function" onclick="orderreturned('{{$param['order']->id}}', '{{htmlspecialchars($param['order']->name, ENT_QUOTES)}}')"><span class="far fa-check-circle"></span>&#160;Grąžinau</button>@endif
                @endif
            </div>
          </div>
        </div>
        <div class="card bg-dark" style="margin-top:20px !important">
          <div class="card-header">
            Užsakymo istorija
          </div>
            <div class="card-body text-light remove-all-padding">
              @foreach ($param['actions'] as $a)
                <div class="row history-row remove-all-margin" style=":hover{cursor: default !important;}">
                  <div class="col-sm-9">
                    {!!$a->stringOutput()!!}
                  </div>
                  <div class="col-sm-3 text-right">
                    {{$a->created_at}}
                  </div>
                </div>
              @endforeach
            </div>
          </div>
      </div>
    </div>
  </div>

@endsection
