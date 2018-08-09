@extends('layouts.main')

@section('content')
  @include('include.category-add-modal')
  @include('include.item-add-modal')
  <script>
  var items = {!!$param['items']!!};
  jQuery.fn.center = function () {
    if ($(window).width() <= 768) {
        this.css("position","absolute");
    }
    else {
        this.css("position","fixed");
    }
    this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + $(window).scrollLeft()) + "px");
    return this;
}

  function toggleGroupDesc(box, id){

     $('.popup-item-box').css('width', $('.container').width());
     $('#'+id).css('height', Object.keys(items[id]).length*55+55);
     console.log(items[id]);
     $('#'+id).center();
    if ($('#'+id).css('display') === 'none')
    {
        $('.item-box-panel').css('box-shadow', '0px 0px 0px 5px #0F3F75');
        box.css('box-shadow', '0px 0px 0px 10px #C02222');
        $('.popup-item-box').hide('400');
        $('#'+id).show('400');
    }
    else{
        box.css('box-shadow', '0px 0px 0px 5px #0F3F75');
        $('.popup-item-box').hide('400');
    }
    };

  </script>

  <div class="container">
    <div class="row row-title navbar-keytracker">
      Įrankių kategorijos
    </div>
    @if(count($param['itemGroups']) == 0)
      <div class="row">
        <div class="col-md-2" style="padding: 10px">
          <div class="item-box-panel" onclick="$('#categoryAdd').modal()">
              <div class="item-add-box" style="height: 100% !important">
                  <span class="fas fa-plus text-success"></span>
              </div>
            </div>
          </div>
        </div>
    @endif
        @for ($i = 0; $i <= count($param['itemGroups'])-1; $i +=6)

              @if($i == 0)
                <div class="row">
                  <div class="col-md-2" style="padding: 10px">
                    <div class="item-box-panel" onclick="$('#categoryAdd').modal()">
                        <div class="item-add-box" style="height: 100% !important">
                            <span class="fas fa-plus text-success"></span>
                        </div>
                      </div>
                    </div>
                @for ($j=0; $j < 5; $j++)
                    @if(isset($param['itemGroups'][$i+$j]))
                    <div class="col-md-2" style="padding: 10px" {{--onclick="toggleGroupDesc($(this).offset().left,'{{$param['itemGroups'][$i+$j]->ItemGroupID}}')"--}}>
                      @component('components.group-box')
                        @slot('itemGroupId')
                          {{$param['itemGroups'][$i+$j]->ItemGroupID}}
                        @endslot
                          @slot('image')
                              @if($param['itemGroups'][$i+$j]['ItemGroupImage'] != null)
                                  <img src="{{asset('media/uploads/'.urlencode(Auth::user()->ClientID)."/".$param['itemGroups'][$i+$j]['ItemGroupImage'])}}" alt="item-img" class="item-img"/>
                              @else
                                  <div class="item-noimg">
                                      <span class="far fa-folder-open text-warning"></span>
                                  </div>
                              @endif
                          @endslot
                          @slot('groupName')
                              {{$param['itemGroups'][$i+$j]['ItemGroupName']}}
                          @endslot
                      @endcomponent
                        @include('include.item-list')
                    </div>
                    @endif
                @endfor
                </div>
                @php
                    $i -= 1;
                @endphp
              @else
                <div class="row">
                @for ($j=0; $j < 6; $j++)
                    @if(isset($param['itemGroups'][$i+$j]))
                    <div class="col-md-2" style="padding: 10px">
                        @component('components.group-box')
                          @slot('itemGroupId')
                            {{$param['itemGroups'][$i+$j]->ItemGroupID}}
                          @endslot
                            @slot('image')
                                @if($param['itemGroups'][$i+$j]['ItemGroupImage'] != null)
                                    <img src="{{asset('media/uploads/'.urlencode(Auth::user()->ClientID)."/".$param['itemGroups'][$i+$j]['ItemGroupImage'])}}" alt="item-img" class="item-img"/>
                                @else
                                    <div class="item-noimg">
                                        <span class="far fa-folder-open text-warning"></span>
                                    </div>
                                @endif
                            @endslot
                            @slot('groupName')
                                {{$param['itemGroups'][$i+$j]['ItemGroupName']}}
                            @endslot
                        @endcomponent
                        @include('include.item-list')
                    </div>
                    @endif
                @endfor
            </div>
          @endif
        @endfor

    </div>
<script>
  $(document).ready(function(){
    $('a[data-toggle="tooltip"]').tooltip({
      container: 'body'
    });
  });
</script>
@endsection
