<div class="item-box-panel" onclick="toggleGroupDesc($(this),'{{$itemGroupId}}')">
    <div class="item-image-box">
        {{$image}}
    </div>
    <div class="row item-name-field remove-all-margin">
        <div class="col-9 item-text remove-all-padding">
          {{$groupName}}
        </div>
        <div class="col-3 text-right remove-all-padding">
          <a class="item-status"><span class="fas fa-angle-double-down text-warning"></span></a>
        </div>
    </div>
</div>
