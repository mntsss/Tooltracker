<div id="categoryAdd" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title danger">Pridėti įrankių grupę</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" method="POST" action="{{route('itemgroup.create')}}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name" class="col-md-4 control-label">Pavadinimas</label>

                <div class="col-md-6">
                    <input id="name" type="name" class="form-control" name="name" value="{{old('name')}}" maxlength="25" required autofocus>

                    @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                    <label for="image" class="col-md-4 control-label">Atvaizdas</label>

                    <div class="col-md-6 text-right">
                         <input name="image" type="file" id="image" class="form-control" accept="image/*" capture>
                         @if ($errors->has('image'))
                             <span class="invalid-feedback">
                                 <strong>{{ $errors->first('image') }}</strong>
                             </span>
                         @endif
                    </div>
                </div>

            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                    <button type="submit" class="btn btn-primary">
                        Pridėti
                    </button>
                </div>
            </div>
        </form>
      </div>
    </div>

  </div>
</div>
