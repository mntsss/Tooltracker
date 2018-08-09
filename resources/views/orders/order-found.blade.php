@extends('layouts.main')

@section('content')
  <script>
  navigator.geolocation.getCurrentPosition(function(position) {
    $('#cooX').val(position.coords.latitude);
    $('#cooY').val(position.coords.longitude);
});
  function getAddress(){
    var latitude = $('#cooX').val();
    var longitude = $('#cooY').val();

    $.ajax({
      accepts: {mycustomtype: 'application/json'},
      method: 'get',
      url: 'https://maps.googleapis.com/maps/api/geocode/json?latlng='+latitude+','+longitude+'&key=AIzaSyBbxtYPVuS5O-HzP59scvJksaWLZL0byCQ',
    }).done(function ( result ){
      $('#orderAddress').val(result['results'][0]['address_components'][1]['short_name']+" "+result['results'][0]['address_components'][0]['short_name']+" "+result['results'][0]['address_components'][2]['short_name']);
    });
  }
  </script>
  <div class="container">
    <div class="row justify-content-center">
      <div class="card bg-dark" style="margin-top: 15px !important">
        <div class="card-header">
          Iš kur gauta
        </div>
        <div class="card-body">
          <form class="form-horizontal" method="post" action="{{route('order.found.submit')}}">
            {{ csrf_field() }}
            <div class="form-group row">
              <input type="text" id="orderAddress" name="orderAddress" class="form-control col-9" value="{{old('orderAddress')}}" maxlength="50" placeholder="Adresas" required />
              <div class="col-3">
                <button type="button" class="btn btn-outline-warning" onclick="getAddress()"><span class="fas fa-map-marker-alt"></span></button>
              </div>
            </div>
            <div class="form-group row">
                <input type="tel" name="orderPhone" class="form-control" value="{{old('orderPhone')}}" maxlength="15" placeholder="Telefono nr." required />
                <input id="cooX" type="hidden" name="cooX" />
                <input id="cooY" type="hidden" name="cooY" />
                <input type="hidden" name="id" value="{{$id}}"/>
            </div>
            <div class="row form-group justify-content-center">
              <button type="submit" class="btn-outline-success">Išsaugoti</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection
