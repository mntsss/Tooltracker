@extends('layouts.main')
@section('content')
  <div class="container">
    <div class="row justify-content-center">
    @include("include.filter-history")

    <div class="card bg-dark" style="margin-top:20px !important">
        <div class="card-body text-light remove-all-padding">
          @foreach ($param['actions'] as $a)
            <div class="row history-row remove-all-margin" onclick="window.location='{{route('order.view', ['id' => $a->orderId])}}';">
              <div class="col-sm-9">
                {!!$a->stringOutput()!!}
              </div>
              <div class="col-sm-3 text-right">
                {{$a->created_at}}
              </div>
            </div>
          @endforeach
          <div class="col-12 text-center center-content border border-dark" style="margin-top: 15px !important">
            {{$param['actions']->appends(['query' => $param['lastSearch']['query'], 'user' => $param['lastSearch']['user'], 'from' => $param['lastSearch']['from'], 'til' => $param['lastSearch']['til']])->links()}}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
