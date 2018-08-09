@extends('layouts.main')

@section('content')
  @include('modals.add-calendar-event')
  @include('modals.view-calendar-event')
  <link href='{{asset('css/fullcalendar.min.css')}}' rel='stylesheet' />
  <link href='{{asset('css/fullcalendar.print.min.css')}}' rel='stylesheet' media='print' />
  <link href='https://bootswatch.com/4/superhero/bootstrap.min.css' rel="stylesheet" />
  <script src='{{asset('js/calendar/moment.min.js')}}'></script>
  <script src='{{asset('js/calendar/fullcalendar.min.js')}}'></script>
  <script src='{{asset('js/calendar/lt.js')}}'></script>

  <script>

  $(document).ready(function() {
    $('#add-event').on('shown.bs.modal', function() {
      $('#event').focus();
    });


    $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay'
      },
    events: fetchEvents(),
    defaultView: 'agendaWeek',
    weekends: false,
    defaultTimedEventDuration: '00:20:00',
    themeSystem: 'bootstrap4',
    slotLabelFormat: 'HH:mm',
	  minTime: '08:40:00',
	  maxTime: '18:20:00',
	  locale: 'lt',
	  slotDuration: '00:20:00',
    slotLabelInterval: '00:20:00',
    eventDurationEditable: false,
      defaultDate: '{{$param['today']}}',
      navLinks: true, // can click day/week names to navigate views
      selectable: true,
      selectHelper: true,
      select: function(start, end) {
        $('#eventStart').val(start);
        $('#eventEnd').val(end);
        $('#add-event').modal();
        (function($){
          $('#event').focus();
        })(jQuery);
      },
      eventClick: function(eventObj) {
          $('#view-event-start').html('<small>'+eventObj.start['_i']+'</small>');
          $('#view-event-name').html(eventObj.title);
          $('#view-event-phone').html('<small>'+eventObj.phone+'</small>');
          $('#event_id').val(eventObj.id);
          $('#view-event').modal();
      },
      eventDrop: function( eventObj, jsEvent, ui, view )
      {
        $.ajax({
          url: '/calendar/event/move',
          type: 'POST',
          data: {_token: "{{ csrf_token() }}", id: eventObj.id, start: eventObj.start.format()},
          dataType: 'html',
          success: function(data){
            if(data == "Ok"){
              fetchEvents();
            }
          }
        });
      },
      editable: true,
      eventLimit: true, // allow "more" link when too many events
    });

  });

  function fetchEvents(){
    $('#calendar').fullCalendar('removeEvents');
    $.ajax({
      url: '{{route('calendar.feed')}}',
      type: 'get',
      dataType: 'json',
      error: function() {
        $('#script-warning').show();
      },
      success: function(data){
        $('#calendar').fullCalendar('addEventSource', data);
      }
    });
    }

  function addEvent(){
    if($('#event').val() == "")
    {
      $('#event').addClass('is-invalid');
      return;
    }
    else if ($('#clientPhone').val() == "") {
      $('#clientPhone').addClass('is-invalid');
      return;
    }
    else{
      $('#event').removeClass('is-invalid');
      $('#clientPhone').removeClass('is-invalid');
      $('#add-event').modal('hide');
      $.ajax({
        url: '/calendar/event/add',
        type: 'POST',
        dataType: 'html',
        data: {_token: "{{ csrf_token() }}", eventName: $('#event').val(), clientPhone: $('#clientPhone').val(), eventStart: moment($('#eventStart').val()).parseZone().format('YYYY-MM-DD HH:mm:ss')},
        success: function(data){
            if(data == "Ok")
            {
              fetchEvents();
            }
          }
        });
        $('#event').val('');
        $('#eventStart').val('');
        $('#eventEnd').val('');
        $('#clientPhone').val('');
    }

  }

  function removeEvent(){
    var id = $('#event_id').val();
    $.ajax({
      url: '/calendar/event/remove/'+id,
      type: 'GET',
      dataType: 'html',
      success: function(data){
        if(data == "Ok"){
          fetchEvents();
        }
      }
    });
    $('#view-event').modal('hide');
  }
  $(document).keypress(
    function(event){
     if (event.which == '13') {
        event.preventDefault();
        if($('#add-event').is(':visible')){
          addEvent();
        }
      }
});

</script>
  <div class="container">
    <div id='calendar'></div>    
  </div>



@endsection
