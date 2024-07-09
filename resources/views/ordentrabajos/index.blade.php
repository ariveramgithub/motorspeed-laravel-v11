@extends('layouts.app')

@section('content')

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Órdenes de trabajo</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-3 offset-md-7">
        <form method="GET" action="{{ route('ordentrabajos.index') }}">
            <div class="input-group">
                <input name="query" type="text" class="form-control" placeholder="Buscar por palabra" aria-label="Buscar por palabra" value="{{ $querystring }}">
                <button class="btn btn-primary" type="submit" aria-label="Search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                    </svg>
                </button>
            </div>
        </form>
        </div>
        <div class="col-md-1">
            <div class="input-group">
                <a href="{{ url('/ordentrabajos/create') }}" class="btn btn-primary" aria-label="Add" title="Agregar">
                <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" fill="currentColor" class="bi bi-plus-square-fill" viewBox="0 0 16 16">
                        <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm6.5 4.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3a.5.5 0 0 1 1 0"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <hr>
    <div class="row justify-content-center">
        <table class="table table-striped table-hover table-borderless">
            <thead>
                <tr>
                    <th scope="col">#ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">RUT</th>
                    <th scope="col">Patente</th>
                    <th scope="col">Inicio</th>
                    <th scope="col">Término</th>
                    <th scope="col">Costo</th>
                    <th scope="col">Estado</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ordenes as $row)
                <tr>
                    <th scope="row">{{ $row->id }}</th>
                    <td>{{ $row->cliente_nombre }}</td>
                    <td>{{ $row->cliente_rut }}</td>
                    <td>{{ $row->vehiculo_patente }}</td>
                    <td>{{ $row->inicio }}</td>
                    <td>{{ $row->termino }}</td>
                    <td>{{ ($row->valor) > 0 ? '$'.number_format($row->valor, 0, ',', '.') : 0 }}</td>
                    <td>{{ $row->estado }}</td>
                    <td>
                        <a href="{{ url('/ordentrabajos').'/'.$row->id.'/edit' }}" title="Editar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                            </svg>
                        </a>
                    </td>
                </tr>
                @endforeach

                @if ($perPage - $countItems > 0)
                @for ($i = 0; $i < $perPage - $countItems; $i++)
                <tr>
                    <th scope="row">-</th>
                    <!--<td></td>-->
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endfor
                @endif
            </tbody>
        </table>
    </div>
    <div class="row">
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <!-- página anterior -->
                <li @class([
                    'page-item',
                    'disabled' => (!$previousPageUrl) ? true : false,
                ])>
                    <a class="page-link" href="{{ $previousPageUrl }}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <!-- fin -->
                
                @for ($i = 1; $i <= $lastPage; $i++)
                <li @class([
                    'page-item',
                    'active' => ($i === $currentPage) ? true : false,
                ])><a class="page-link" href="/ordentrabajos?page={{ $i }}{{ ($querystring != '') ? '&query=' . $querystring : '' }}">{{ $i }}</a></li>
                @endfor

                <!-- página siguiente -->
                <li @class([
                    'page-item',
                    'disabled' => (!$nextPageUrl) ? true : false,
                ])>
                    <a class="page-link" href="{{ $nextPageUrl }}" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
                <!-- fin -->
            </ul>
        </nav>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
</div>

@endsection