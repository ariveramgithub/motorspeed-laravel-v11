@extends('layouts.app')

@push('styles')
<style>
#addEventButton {
  position: fixed;
  bottom: 0;
  right: 0;
  z-index: 1030;
}
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.13/index.global.js" integrity="sha512-lgwvOY58GXc17idSOR6jzt0vCT9ZVtIuqVAV63YoVSj+OlsNzB+RvefYXZ0I6jbwXG2fcuzLRUIsdfp4EWSUEA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
const appendAlert = (message, type) => {
  const alertPlaceholder = document.getElementById('liveAlertPlaceholder');
  const wrapper = document.createElement('div');
  wrapper.innerHTML = [
    `<div class="alert alert-${type} alert-dismissible" role="alert">`,
    `   <div>${message}</div>`,
    '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
    '</div>'
  ].join('');
  alertPlaceholder.innerHTML = "";
  alertPlaceholder.append(wrapper);
};

let calendar = null;
document.addEventListener('DOMContentLoaded', function() {
  const calendarElement = document.getElementById('calendar');
  calendar = new FullCalendar.Calendar(calendarElement, {
    themeSystem: 'standar',
    eventBackgroundColor: '#ea7640',
    eventBorderColor: '#ea7640',
    eventTextColor: 'white',
    locale: 'es',
    timeZone: 'local',
    firstDay: 1,
    navLinks: true,
    buttonText: {
        today:    'Hoy',
        month:    'Mes',
    },
    headerToolbar: {
        left: 'prevYear,prev,next,nextYear',
        center: 'title',
        right: 'today dayGridMonth'
    },
    eventClick: function(info) {
      info.jsEvent.preventDefault();
      $("#eventStartLi").html('<span class="fw-bold">Inicio</span><p>' + info.event.extendedProps.inicio + '</p>');
      $("#eventNameLi").html('<span class="fw-bold">Título</span><p>' + info.event.title + '</p>');
      $("#eventDescriptionLi").html('<span class="fw-bold">Descripción</span><p>' + info.event.extendedProps.description + '</p>');

      if( info.event.url ){
        $("#eventLinkLi").html('<a href="' + info.event.url + '">Ir a la Órden</a>');
        $("#eventLinkLi").show();
      } else {
        $("#eventLinkLi").hide();
      }

      $("#idEventHidden").val(info.event.extendedProps.pkId);
      $("#openDetailEventOffCanvasButton").click();
    },
    events: {
      url: '{{ route("calendario.getEvents") }}',
    },
  });

  calendar.render();
});

$(document).ready(function(){
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $("#addEventForm").on("submit", function(e){
    e.preventDefault();
    $.ajax({
      url: '{{ route("calendario.addEvent") }}',
      data: $('#addEventForm').serialize(),
      type: "POST",
      success: function (data) {
        calendar.refetchEvents();
        $("#closeModalAddEventButton").click();
        appendAlert('{{ __("Evento grabado exitósamente") }}', 'success');
        $("#addEventForm").get(0).reset();
        reloadBellEvents();
      },
      error: function (err) {
        console.error(err);
        $("#closeModalAddEventButton").click();
        appendAlert('{{ __("Se produjo un error al intentar grabar evento. Revisar consola de errores") }}', 'danger');
      },
    });
  });
  $("#deleteEventForm").on("submit", function(e){
    e.preventDefault();
    $.ajax({
      url: '{{ route("calendario.deleteEvent") }}',
      data: $('#deleteEventForm').serialize(),
      type: "POST",
      success: function (data) {
        calendar.refetchEvents();
        $("#openDetailEventOffCanvasButton").click();
        appendAlert('{{ __("Evento eliminado exitósamente") }}', 'success');
        reloadBellEvents();
      },
      error: function (err) {
        console.error(err);
        $("#openDetailEventOffCanvasButton").click();
        appendAlert('{{ __("Se produjo un error al intentar eliminar evento. Revisar consola de errores") }}', 'danger')
      },
    });
  });
});

console.info('Server Time: {{ $serverTime }}');
console.info('Client Time:', new Date());
</script>
@endpush

@section('content')
<button id="openDetailEventOffCanvasButton" class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#detailEventOffCanvas" aria-controls="detailEventOffCanvas" style="display:none">
  Open OffCanvas
</button>
<div id="detailEventOffCanvas" class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas" aria-labelledby="offcanvasLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasLabel">Detalle Evento</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <ul class="list-group list-group-horizontal">
      <li id="eventStartLi" class="list-group-item col-12"></li>
    </ul>
    <ul class="list-group">
      <li id="eventNameLi" class="list-group-item"></li>
      <li id="eventDescriptionLi" class="list-group-item"></li>
      <li id="eventLinkLi" class="list-group-item"></li>
      <li class="list-group-item">
        <form id="deleteEventForm">
          <input type="hidden" id="idEventHidden" name="event_pk" value="">
          <button class="btn btn-primary" aria-label="delete" title="Borrar">
            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
            </svg>
          </button>
        </form>
      </li>
    </ul>
  </div>
</div>

<div class="container">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ __('Calendario de Eventos') }}</li>
    </ol>
  </nav>
  <div id='calendar'></div>
  <div id="liveAlertPlaceholder"></div>
</div>

<button id="addEventButton" type="button" class="btn btn-primary m-3" data-bs-toggle="modal" data-bs-target="#addEventModal">
  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="20" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
    <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
  </svg>
</button>

<div id="addEventModal" class="modal fade" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
    <form id="addEventForm" action="{{ route('calendario.addEvent') }}">
      <div class="modal-header">
        <h5 class="modal-title">Agregar evento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="form-floating mb-3">
            <input name="event_name" value="" type="text" class="form-control" id="inputEventName" placeholder="" required >
            <label for="inputEventName">Título (*)</label>
          </div>
          <div class="form-floating mb-3">
            <textarea name="event_description" class="form-control" id="inputEventDescription"></textarea>
            <label for="inputEventDescription">Descripción</label>
          </div>
          <div class="form-floating mb-3">
            <input name="event_start" value="" type="datetime-local" class="form-control" id="inputEventStart" placeholder="" required >
            <label for="inputEventStart">Inicio (*)</label>
          </div>        
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Crear</button>
        <button id="closeModalAddEventButton" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </form>
    </div>
  </div>
</div>
@endsection