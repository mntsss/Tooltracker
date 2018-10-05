@extends('layouts.main')

@section('content')
  <script>
  function loadModels(){
    make = $('#car-make').val();
    $.ajax({
      url: '/vehicles/models',
      type: 'POST',
      dataType: 'html',
      data: {_token: "{{ csrf_token() }}", make: make},
      success: function(data){
          $('#car-model').empty().append(data);
        }
      });
    }
  </script>
  <link rel="stylesheet" type="text/css" href="{{ asset('css/datetimepicker.css')}}"/>
  <div class="container">
    <div class="row mx-0">
      <div class="col-md-10">
        <div class="card bg-dark">
          <div class="card-header">
            Naujas užsakymas
          </div>
          <div class="card-body">
            <form method="POST" action="{{ route('search.add.submit') }}" enctype="multipart/form-data">
                @csrf
                <div class="row mx-0">
                  <div class="col-md">
                    <div class="form-group">
                      <label for="car-makes" class="col-form-label text-md-right">Gamintojas</label>
                      @if(isset($param['previous']['make']))
                        <input type="text" name="make" value="{{$param['previous']['make']}}" class="form-control" readonly />
                      @else
                      <select name="make" id="car-make" class="form-control" onchange="loadModels()" value="{{ old('make') }}">
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
                      <label for="car-models" class="col-form-label text-md-right">Modelis</label>
                      @if(isset($param['previous']['model']))
                        <input type="text" name="model" value="{{$param['previous']['model']}}" class="form-control" readonly />
                      @else
                      <select name="model" id="car-model" class="form-control" value="{{ old('model') }}">
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
                      <label for="car-years" class="col-form-label text-md-right">Metai</label>
                      @if(isset($param['previous']['year']))
                        <input type="text" name="year" value="{{$param['previous']['year']}}" class="form-control" readonly />
                      @else
                      <select name="year" id="car-year" class="form-control" value="{{ old('year') }}">
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
                      <label for="carfuel" class="col-form-label text-md-right">Kuro tipas</label>
                      @if(isset($param['previous']['fuel']))
                        <input type="text" name="fuel" value="{{$param['previous']['fuel']}}" class="form-control" readonly />
                      @else
                      <select name="fuel" id="car-fuel" class="form-control" value="{{ old('fuel') }}">
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
                      <label for="cartype" class="col-form-label text-md-right">Kėbulo tipas</label>
                      @if(isset($param['previous']['cartype']))
                        <input type="text" name="cartype" value="{{$param['previous']['cartype']}}" class="form-control" readonly />
                      @else
                      <select name="cartype" class="form-control" value="{{ old('cartype') }}">
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
                    <label for="carchasis" class="col-form-label text-md-right">Kėbulas</label>
                    @if(isset($param['previous']['carchassis']))
                      <input type="text" name="carchassis" value="{{$param['previous']['carchassis']}}" class="form-control" readonly />
                    @else
                    <input type="text" name="carchassis" class="form-control" value="{{ old('carchassis') }}" placeholder="Pvz.: E46, W212, B5..."/>
                  @endif
                  </div>
                </div>
              </div>

                <div class="form-group row mx-0">
                    <label for="power" class="col-md-4 col-form-label text-md-right">Galingumas (kw)</label>

                    <div class="col-md-6">
                      @if(isset($param['previous']['power']))
                        <input type="number" name="power" value="{{$param['previous']['power']}}" class="form-control" readonly />
                      @else
                        <input id="power" type="number" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" name="power" value="0" required>
                      @endif
                        @if ($errors->has('power'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('power') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row mx-0">
                    <label for="car-models" class="col-md-4 col-form-label text-md-right">VIN kodas</label>

                    <div class="col-md-6">
                        <input id="vin" type="text" class="form-control{{ $errors->has('vin') ? ' is-invalid' : '' }}" name="vin" value="@if(isset($param['previous']['vin'])){{$param['previous']['vin']}}@else{{ old('vin') }}@endif" maxlength="25" style="text-transform:uppercase">

                        @if ($errors->has('vin'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('vin') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row mx-0">
                    <label for="name" class="col-md-4 col-form-label text-md-right">Detalės pavadinimas</label>

                    <div class="col-md-6">
                        <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" autocomplete="off" required>

                        @if ($errors->has('name'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row mx-0">
                    <label for="code" class="col-md-4 col-form-label text-md-right">Delatės kodas</label>

                    <div class="col-md-6">
                        <input id="code" type="text" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" name="code" value="{{ old('code') }}" autocomplete="off">

                        @if ($errors->has('code'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('code') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row mx-0">
                    <label for="desciption" class="col-md-4 col-form-label text-md-right">Detalės aprašymas</label>

                    <div class="col-md-6">
                        <textarea id="description" type="text" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" value="{{ old('description') }}" autocomplete="off"></textarea>

                        @if ($errors->has('description'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>


                <div class="form-group row mx-0">
                    <label for="important" class="col-md-4 col-form-label text-md-right">Skubu (atvežti kuo greičiau)</label>

                    <div class="col-md-6 text-left">
                        <input id="important" type="checkbox" class="form-control{{ $errors->has('important') ? ' is-invalid' : '' }}" name="important" value="1" style="height: 35px">
                    </div>
                </div>

                <div class="form-group row mx-0">
                    <label for="timeLimit" class="col-md-4 col-form-label text-md-right">Laiko limitas (atvežti iki ...)</label>

                    <div class="col-md-6">
                        <input id="timeLimit" type="text" class="form-control{{ $errors->has('timeLimit') ? ' is-invalid' : '' }}" name="timeLimit" value="{{ old('timeLimit') }}">

                        @if ($errors->has('timeLimit'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('timeLimit') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row mx-0">
                    <label for="image" class="col-md-4 col-form-label text-md-right">Nuotrauka</label>

                    <div class="col-md-6 text-left">
                         <input name="image" type="file" id="image" class="form-control{{ $errors->has('image') ? ' is-invalid' : '' }}" accept="image/*" capture>
                         @if ($errors->has('image'))
                             <span class="invalid-feedback">
                                 <strong>{{ $errors->first('image') }}</strong>
                             </span>
                         @endif
                    </div>
                </div>

                <div class="form-group row mx-0 mb-0 justify-content-center">
                        <button type="submit" class="btn btn-outline-light">
                            Išsaugoti
                        </button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="{{asset('js/datetimepicker.js')}}"></script>

          <script>
              jQuery(document).ready(function () {
                  'use strict';
                  jQuery('#timeLimit').datetimepicker();
              });
          </script>
@endsection
