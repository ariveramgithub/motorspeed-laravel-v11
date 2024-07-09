<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <!-- Others CSS -->
    @stack('styles')
    <!-- Others Scripts -->
    @stack('scripts')
</head>
<body>
<script>
    const currentDate = new Date().toLocaleDateString('en-GB');
    const currentTime = new Date().toTimeString().slice(0, 8);
    const timeZone = new Date().toTimeString().slice(12, 17);
    const currentDateTimeZone = `${currentDate.slice(6, 10)}-${currentDate.slice(3, 5)}-${currentDate.slice(0, 2)}T${currentTime}${timeZone.slice(0, 3)}:${timeZone.slice(3, 5)}`;

    const reloadBellEvents = () => {
        fetch('{{ route("calendario.getUpcomingEvents") }}?currentDatetime=' + currentDateTimeZone)
        .then(response => response.json())
        .then((data) => {
            const badge = document.getElementById("badgeUpcomingEvents");
            badge.innerHTML = (data.length > 100) ? "99+" : data.length;
            const dropDownEventList = document.getElementById("dropDownEventList");
            dropDownEventList.innerHTML = `<li><a class="dropdown-item active" href="#" aria-current="true">Eventos próximos</a></li>`;
            const optionsFormat = {
                weekday: 'long',
                month: 'short',
                day: 'numeric',
                hour: 'numeric',
                minute: 'numeric',
            };
            const dateTimeFormat = new Intl.DateTimeFormat('es-ES', optionsFormat);

            if(data.length > 0){
                for( e in data ){
                    const event = new Date(data[e].event_start);

                    if( data[e].event_relationship === 'orden_trabajos' && data[e].event_id ) {
                        dropDownEventList.innerHTML += `<li><h6 class="dropdown-header">${dateTimeFormat.format(event)}</h6></li>`;
                        dropDownEventList.innerHTML += `<li><a class="dropdown-item" href="{{ url('/ordentrabajos') }}/${data[e].event_id}/edit">${data[e].event_name}</a></li>`;
                    } else {
                        dropDownEventList.innerHTML += `<li><h6 class="dropdown-header">${dateTimeFormat.format(event)}</h6></li>`;
                        dropDownEventList.innerHTML += `<li><a class="dropdown-item" href="#">${data[e].event_name}</a></li>`;
                    }
                }

                document.getElementById("bellNotificationEventsOn").style = "display:inline";
                document.getElementById("bellNotificationEventsOff").style = "display:none";
            } else {
                document.getElementById("bellNotificationEventsOn").style = "display:none";
                document.getElementById("bellNotificationEventsOff").style = "display:inline";
            }
        });
    }

    reloadBellEvents();
</script>

    <div id="app">
        <nav class="navbar navbar-expand-md shadow-sm p-3">
            <div class="container col-md-12">
                <a class="navbar-brand fw-semibold" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                        @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item">
                            <div class="dropstart">
                                <button type="button" class="btn btn-link position-relative" data-bs-toggle="dropdown">
                                    <svg id="bellNotificationEventsOn" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#ed4b00" class="bi bi-bell-fill" viewBox="0 0 16 16" style="display:none">
                                        <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"/>
                                    </svg>
                                    <svg id="bellNotificationEventsOff" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#ed4b00" class="bi bi-bell" viewBox="0 0 16 16">
                                        <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4 4 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4 4 0 0 0-3.203-3.92zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5 5 0 0 1 13 6c0 .88.32 4.2 1.22 6"/>
                                    </svg>
                                    <span id="badgeUpcomingEvents" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"></span>
                                </button>
                                <ul id="dropDownEventList" class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Action</a></li>
                                    <li><a class="dropdown-item" href="#">Another action</a></li>
                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>