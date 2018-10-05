@extends('layouts.main')

@section('content')
  <script>
  function loadModels(){
    make = $('#vehicleMake').val();
    $.ajax({
      url: 'vehicles/models',
      type: 'POST',
      dataType: 'html',
      data: {_token: "{{ csrf_token() }}", make: make},
      success: function(data){
          $('#vehicleModel').empty().append(data);
        }
      });
    }
  </script>
  <form action="" method="post">
  <div class="row mx-0 justify-content-center">
    <div class="col-md-6 flex-column align-self-stretch">
      <div class="card">
        <div class="card-header bg-dark">
          <span class="fas fa-user"></span> Klientas
        </div>
        <div class="card-body">
          <div class="form-group">
            <label for="clientName">Vardas, pavardė</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-user"></i></span>
              <input type="text" class="form-control" name="clientName" required />
            </div>
          </div>
          <div class="form-group">
            <label for="clientPhone">Telefonas</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-phone"></i></span>
              <input type="text" class="form-control" name="clientPhone" required />
            </div>
          </div>
          <div class="form-group">
            <label for="clientCompany">Įmonė</label>
            <div class="input-group">
              <span class="input-group-text"><i class="far fa-building"></i></span>
              <input type="text" class="form-control" name="clientCompany" />
            </div>
          </div>
          <div class="form-group">
            <label for="clientCompanyCode">Įmonės kodas</label>
            <div class="input-group">
              <span class="input-group-text"><i class="far fa-address-card"></i></span>
              <input type="text" class="form-control" name="clientCompanyCode" />
            </div>
          </div>
          <div class="form-group">
            <label for="clientEmail">El. paštas</label>
            <div class="input-group">
              <span class="input-group-text"><i class="far fa-envelope"></i></span>
              <input type="email" class="form-control" name="clientEmail" />
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6 flex-column disableddiv">
      <div class="card">
        <div class="card-header bg-dark">
          <span class="fas fa-car"></span>&#160;Automobilis
        </div>
        <div class="card-body">
          <div class="row mx-0">
            <div class="col-md">
              <div class="form-group">
                <label for="vehicleMake" class="col-form-label text-md-right">Gamintojas</label>
                @if(isset($param['previous']['vehicleMake']))
                  <input type="text" name="vehicleMake" value="{{$param['previous']['vehicleMake']}}" class="form-control" readonly />
                @else
                <select name="vehicleMake" id="vehicleMake" class="form-control" onchange="loadModels()" value="{{ old('vehicleMake') }}">
                  <option value="">
                    -Pasirinkite-
                  </option>
                  @foreach($param['makes'] as $m)
                    <option value="{{$m->make}}">
                      {{$m->make}}
                    </option>
                  @endforeach
                </select>
              @endif
              </div>
            </div>
            <div class="col-md">
              <div class="form-group">
                <label for="vehicleModel" class="col-form-label text-md-right">Modelis</label>
                @if(isset($param['previous']['vehicleModel']))
                  <input type="text" name="vehicleModel" value="{{$param['previous']['vehicleModel']}}" class="form-control" readonly />
                @else
                <select name="vehicleModel" id="vehicleModel" class="form-control" value="{{ old('vehicleModel') }}">
                  <option>
                    --
                  </option>
                </select>
              @endif
              </div>
            </div>
          </div>
          <div class="row mx-0">
            <div class="col-md">
              <div class="form-group">
                <label for="vehicleYear" class="col-form-label text-md-right">Metai</label>
                @if(isset($param['previous']['vehicleYear']))
                  <input type="text" name="vehicleYear" value="{{$param['previous']['vehicleYear']}}" class="form-control" readonly />
                @else
                <select name="vehicleYear" id="vehicleYear" class="form-control" value="{{ old('vehicleYear') }}">
                  <option value="">
                    -Pasirinkite-
                  </option>
                  @for($y =  $param['years']; $y >= 1975; $y--)
                    <option value="{{$y}}">
                      {{$y}}
                    </option>
                  @endfor
                </select>
              @endif
              </div>
            </div>
            <div class="col-md">
              <div class="form-group">
                <label for="vehicleFuel" class="col-form-label text-md-right">Kuro tipas</label>
                @if(isset($param['previous']['vehicleFuel']))
                  <input type="text" name="vehicleFuel" value="{{$param['previous']['vehicleFuel']}}" class="form-control" readonly />
                @else
                <select name="vehicleFuel" id="vehicleFuel" class="form-control" value="{{ old('vehicleFuel') }}">
                  <option value="">--</option>
                  <option value="Dyzelinas">Dyzelinas</option>
                  <option value="Benzinas">Benzinas</option>
                  <option value="Benzinas / dujos">Benzinas / dujos</option>
                  <option value="Benzinas / elektra">Benzinas / elektra</option>
                  <option value="Elektra">Elektra</option>
                  <option value="Dyzelinas / elektra">Dyzelinas / elektra</option>
                  <option value="Dyzelinas / dujos">Dyzelinas / dujos</option>
                  <option value="Bioetanolis (E85)">Bioetanolis (E85)</option>
                  <option value="Kita">Kita</option>
                </select>
              @endif
              </div>
            </div>
          </div>
          <div class="row mx-0">
            <div class="col-md">
              <div class="form-group">
                <label for="vehicleType" class="col-form-label text-md-right">Kėbulo tipas</label>
                @if(isset($param['previous']['vehicleType']))
                  <input type="text" name="vehicleType" value="{{$param['previous']['vehicleType']}}" class="form-control" readonly />
                @else
                <select name="vehicleType" class="form-control" value="{{ old('vehicleType') }}">
                  <option value="">--</option>
                  <option value="Sedanas">Sedanas</option>
                  <option value="Hečbekas">Hečbekas</option>
                  <option value="Universalas">Universalas</option>
                  <option value="Vienatūris">Vienatūris</option>
                  <option value="Visureigis">Visureigis</option>
                  <option value="Kupė">Kupė</option>
                  <option value="Komercinis">Komercinis</option>
                  <option value="Kabrioletas">Kabrioletas</option>
                  <option value="Limuzinas">Limuzinas</option>
                  <option value="Pikapas">Pikapas</option>
                  <option value="Kita">Kita</option></select>
                </select>
              @endif
              </div>
            </div>
          <div class="col-md">
            <div class="form-group">
              <label for="vehicleChassis" class="col-form-label text-md-right">Kėbulas</label>
              @if(isset($param['previous']['vehicleChassis']))
                <input type="text" name="vehicleChassis" value="{{$param['previous']['vehicleChassis']}}" class="form-control" readonly />
              @else
              <input type="text" name="vehicleChassis" class="form-control" value="{{ old('vehicleChassis') }}" placeholder="Pvz.: E46, W212, B5..." maxlength="15"/>
            @endif
            </div>
          </div>
        </div>
        <div class="row mx-0">
          <div class="col-md">
            <div class="form-group">
              <label for="vehiclePower" class="col-form-label text-md-right">Galingumas (kw)</label>
              @if(isset($param['previous']['vehiclePower']))
                <input type="number" name="vehiclePower" value="{{$param['previous']['vehiclePower']}}" class="form-control" readonly />
              @else
                <input id="vehiclePower" type="number" class="form-control{{ $errors->has('vehiclePower') ? ' is-invalid' : '' }}" name="power" value="0">
              @endif
                @if ($errors->has('vehiclePower'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('vehiclePower') }}</strong>
                    </span>
                @endif
            </div>
          </div>
          <div class="col-md">
              <div class="form-group">
                <label for="vehicleVin" class="col-form-label text-md-right">VIN kodas</label>
                <input id="vehicleVin" type="text" class="form-control{{ $errors->has('vehicleVin') ? ' is-invalid' : '' }}" name="vehicleVin" value="{{ old('vehicleVin') }}" maxlength="25" style="text-transform:uppercase" required>

                @if ($errors->has('vehicleVin'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('vehicleVin') }}</strong>
                    </span>
                @endif
              </div>
          </div>
        </div>

        <div class="row mx-0">
          <div class="col-md">
              <div class="form-group">
                <label for="vehiclePlates" class="col-form-label text-md-right">Valstybinis numeris</label>
                <input id="vehiclePlates" type="text" class="form-control{{ $errors->has('vehiclePlates') ? ' is-invalid' : '' }}" name="vehiclePlates" value="{{ old('vehiclePlates') }}" maxlength="6" style="text-transform:uppercase">

                @if ($errors->has('vehiclePlates'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('vehiclePlates') }}</strong>
                    </span>
                @endif
              </div>
          </div>
          <div class="col-md">
              <div class="form-group">
                <label for="vehicleCapacity" class="col-form-label text-md-right">Kubatūra</label>
                <input id="vehicleCapacity" type="number" class="form-control{{ $errors->has('vehicleCapacity') ? ' is-invalid' : '' }}" name="vehicleCapacity" value="0">

                @if ($errors->has('vehicleCapacity'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('vehicleCapacity') }}</strong>
                    </span>
                @endif
              </div>
          </div>
        </div>

        </div>
      </div>
    </div>
  </div>
  </form>
@endsection
