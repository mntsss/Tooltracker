<div id="view-event" class="modal fade" role="dialog" aria-labelledby="viewCalendarEvent" aria-hidden="true" tabindex="-1">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content bg-dark text-light">
      <div class="modal-header">
        <h5 class="modal-title text-white">Įrašas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body ">
        <div class="text-left text-white" id="view-event-start">
        </div>
        <div class="text-left text-white" id="view-event-name">
        </div>
        <div class="text-left text-white" id="view-event-phone">
        </div>
        <input type="hidden" name="event_id" id="event_id" />
      </div>
      <div class="modal-footer row">
        <div class="col-12 justify-content-end">
          <button type="button" class="btn btn-outline-success">Atvyko</button>
          <button type="button" class="btn btn-outline-danger" onclick="removeEvent()">Ištringi</button>
        </div>
      </div>
    </div>

  </div>
</div>
