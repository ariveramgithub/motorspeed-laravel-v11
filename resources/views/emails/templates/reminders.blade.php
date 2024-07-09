<!DOCTYPE html>
<html>
<head>
    <title>MotorSpeed Performance Recordatorio de eventos</title>
</head>
<body>
    <h2>Recordatorio de Actividades para MotorSpeed Performance</h2>
    <h3>Este correo es enviado de forma automática con el objetivo de informarte de las próximas actividades que se acercan según tu calendario de eventos:</h3>
    
    <ul>
        @foreach ($reminders as $row)
        <li>{{ date('M d, Y H:i', strtotime($row->event->event_start)) }} - {{ $row->event->event_name }}</li>
        @endforeach
    </ul>

    <p>Para más información accede a tu calendario haciendo click <a href="{{ route("calendario.index") }}">aquí</a></p>
</body>
</html>