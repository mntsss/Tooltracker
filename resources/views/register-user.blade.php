@extends('layouts.manager')

@section('content')
  <div class="container">
    <div class="row mx-0 justify-content-center">
      <div class="col-10">
        <div class="card bg-dark">
          <div class="card-header">
            Naujo vartotojo registracija
          </div>
          <div class="card-body">
            <form class="form-horizontal" method="post" action="{{route('user.register.submit')}}">
              @csrf
              <div class="col-md">
                <div class="form-group row mx-0">
                  <label for="email" class="col-md-4 col-form-label text-md-right">El. paštas</label>
                  <input type="email" name="email" class="col-md-6 form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" required autofocus />
                  @if ($errors->has('name'))
                      <span class="invalid-feedback">
                          <strong>{{ $errors->first('email') }}</strong>
                      </span>
                  @endif
                </div>
              </div>
              <div class="col-md">
                <div class="form-group row mx-0">
                  <label for="password" class="col-md-4 col-form-label text-md-right">Slaptažodis</label>
                  <input type="password" name="password" class="col-md-6 form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required />
                  @if ($errors->has('password'))
                      <span class="invalid-feedback">
                          <strong>{{ $errors->first('password') }}</strong>
                      </span>
                  @endif
                </div>
              </div>
              <div class="col-md">
                <div class="form-group row mx-0">
                  <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">Pakartikite slaptažodį</label>
                  <input type="password" name="password_confirmation" class="col-md-6 form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" required />
                </div>
              </div>

              <div class="col-md">
                <div class="form-group row mx-0">
                  <label for="name" class="col-md-4 col-form-label text-md-right">Vardas, pavardė</label>
                  <input type="text" name="name" class="col-md-6 form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" required />
                  @if ($errors->has('name'))
                      <span class="invalid-feedback">
                          <strong>{{ $errors->first('name') }}</strong>
                      </span>
                  @endif
                </div>
              </div>
              <div class="col-md">
                <div class="form-group row mx-0">
                  <label for="role" class="col-md-4 col-form-label text-md-right">Pareigos</label>
                  <select name="role" class="form-control col-md-6 {{ $errors->has('role') ? ' is-invalid' : '' }}" required />
                    <option value="Vadybininkas">
                      Vadybininkas
                    </option>
                    <option value="Tiekėjas">
                      Tiekėjas
                    </option>
                  </select>
                  @if ($errors->has('role'))
                      <span class="invalid-feedback">
                          <strong>{{ $errors->first('role') }}</strong>
                      </span>
                  @endif
                </div>

                <div class="form-group row mx-0 mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            Registruoti
                        </button>
                    </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
