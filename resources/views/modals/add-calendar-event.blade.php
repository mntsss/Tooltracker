<div id="add-event" class="modal fade" role="dialog" aria-labelledby="addCalendarEvent" aria-hidden="true" tabindex="-1">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content bg-dark text-light">
      <div class="modal-header">
        <h5 class="modal-title">Pridėti įrašą</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="post">
          <div class="form-group">
            <label for="event">Įrašas</label>
            <input type="text" class="form-control" name="event" maxlength="154" id="event" required/>
            <input type="hidden" name="eventStart" id="eventStart" />
            <input type="hidden" name="eventEnd" id="eventEnd" />
          </div>
          <div class="form-group">
            <label for="event">Telefonas</label>
            <input type="text" class="form-control" name="clientPhone" maxlength="15" id="clientPhone" required/>
          </div>
          <div class="row mx-0 justify-content-center">
            <button type="button" class="btn btn-outline-success" onclick="addEvent()">Pridėti</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
