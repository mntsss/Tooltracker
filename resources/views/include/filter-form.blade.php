  <link rel="stylesheet" type="text/css" href="{{ asset('css/datetimepicker.css')}}"/>
  <div class="card bg-dark">
  <div class="card-body">
    <form class="row" method="GET" action="{{$slot}}">
      {{csrf_field()}}
        <div class="col-md-3">
            <input type="text" name="query" class="form-control input-margin" value="{{old('query')}}" placeholder="Paieška..."/>
        </div>
        <div class="col-md-3">
              <input type="text" id="datepickerFrom" class="form-control input-margin" name="from" placeholder="Nuo:" />
        </div>
        <div class="col-md-3">
              <input type="text" id="datepickerTil" class="form-control input-margin" name="til" placeholder="Iki:" />
        </div>
        <div class="col-md-2">
              <button type="submit" class="btn btn-outline-warning  input-margin"><span class="fas fa-search"></span></button>
        </div>
    </form>
  </div>
</div>
<script src="{{asset('js/datetimepicker.js')}}"></script>
<script>
  jQuery(document).ready(function () {
      'use strict';
      jQuery('#datepickerFrom').datetimepicker();
      jQuery('#datepickerTil').datetimepicker();
  });
</script>
