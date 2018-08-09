@extends('layouts.manager')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card bg-dark">
                    <div class="card-header">
                        Keisti slaptažodį
                    </div>
                    <div class="card-body">
                        <form class="form-horizontal" action="{{route("user.password.submit")}}" method="post">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('oldpass') ? ' has-error' : '' }}">
                                <label for="oldpass" class="col-md-8 control-label">Senas slaptažodis</label>

                                <div class="col-md-10">
                                    <input id="oldpass" type="password" class="form-control" name="oldpass" required autofocus>

                                    @if ($errors->has('oldpass'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('oldpass') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-8 control-label">Slaptažodis</label>

                                <div class="col-md-10">
                                    <input id="password" type="password" class="form-control" name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password-confirm" class="col-md-8 control-label">Pakartoti slaptažodį</label>

                                <div class="col-md-10">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-warning">
                                        <span class="glyphicon glyphicon-ok"></span> Keisti
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
