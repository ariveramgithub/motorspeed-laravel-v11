@extends('layouts.app')

@section('content')

<style>
#liveAlertVehiculos {
    display: none;
}
#detailOrdenesOffCanvas {
    --bs-offcanvas-height: 80vh;
}
</style>

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{ url('/clientes') }}">Clientes</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edición</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="offset-md-2 col-md-8">
            <div class="card">
                <div class="card-header fs-4 fw-semibold">{{ __('Editar Cliente') }}</div>
                <div class="card-body">
                <p class="card-text">{{ __('Completa todos los datos obligatorios (*) y haz click en "Guardar"') }}</p>
                <form method="POST" action="{{ route('clientes.update', $client->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="input-group">
                        <div class="form-floating mb-3 col-md-6">
                            <input name="rut" value="{{ $client->rut }}" type="text" class="form-control @error('rut') is-invalid @enderror" id="inputRut" placeholder="" required >
                            @error('rut')
                            <label for="inputRut">{{ $message }}</label>
                            @else
                            <label for="inputRut">RUT (*) (sin puntos y con guión)</label>
                            @enderror
                        </div>
                        <div class="form-floating mb-3 col-md-6">
                            <input name="nombre" value="{{ $client->nombre }}" type="text" class="form-control @error('nombre') is-invalid @enderror" id="inputNombre" placeholder="" required >
                            @error('nombre')
                            <label for="inputNombre">{{ $message }}</label>
                            @else
                            <label for="inputNombre">Nombre (*)</label>
                            @enderror
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <input name="direccion" value="{{ $client->direccion }}" type="text" class="form-control @error('direccion') is-invalid @enderror" id="inputDireccion" placeholder="" required >
                        @error('direccion')
                        <label for="inputDireccion">{{ $message }}</label>
                        @else
                        <label for="inputDireccion">Dirección (*)</label>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input name="email" value="{{ $client->email }}" type="text" class="form-control @error('email') is-invalid @enderror" id="inputEmail" placeholder="" required >
                        @error('email')
                        <label for="inputEmail">{{ $message }}</label>
                        @else
                        <label for="inputEmail">Email address (*)</label>
                        @enderror
                    </div>
                    <div class="input-group">
                        <div class="form-floating mb-3 col-md-6">
                            <input name="telefono1" value="{{ $client->telefono1 }}" type="text" class="form-control @error('telefono1') is-invalid @enderror" id="inputTelefono1" placeholder="" required >
                            @error('telefono1')
                            <label for="inputTelefono1">{{ $message }}</label>
                            @else
                            <label for="inputTelefono1">Teléfono (*)</label>
                            @enderror
                        </div>
                        <div class="form-floating mb-3 col-md-6">
                            <input name="telefono2" value="{{ $client->telefono2 }}" type="text" class="form-control" id="inputTelefono2" placeholder="">
                            <label for="inputTelefono2">Otro teléfono</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 justify-content-start">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Volver</a>
                            <a href="{{ route('vehiculos.create').'?client='.$client->id }}" class="btn btn-primary">Agregar Vehículo</a>
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <button id="deleteButton" type="button" class="btn btn-warning">Eliminar</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
            @if ( count($vehiculos) > 0)
            <div class="alert alert-danger" role="alert" id="liveAlertVehiculos">
                {{ __('Al eliminar este registro los vehículos quedarán sin un cliente asociado. ¿Desea continuar?') }}
                <button id="btnContinueSubmit" class="btn btn-primary">Continuar</button>
                <button id="btnCancelSubmit" class="btn btn-secondary">Cancelar</button>
            </div>
            @endif
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-header fs-4 fw-semibold">{{ __('Vehículos') }}</div>
                <div class="card-body">
                    @if( count($vehiculos) > 0 )
                    <p class="card-text">{{ __('Listado de vehículos') }}</p>
                    <ul class="list-group">
                        @foreach ($vehiculos as $vehiculo)
                        <li class="list-group-item"><a href="{{ url('/vehiculos').'/'.$vehiculo->id.'/edit' }}">{{ $vehiculo->marca." ".$vehiculo->modelo }}</a></li>
                        @endforeach
                    </ul>
                    @else
                    <p class="card-text">{{ __('No posee vehículos') }}</p>
                    @endif
                </div>
            </div>
            @if( count($ordenes) > 0 )
            <div class="card">
                <button id="openDetailOrdenesOffCanvasButton" class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#detailOrdenesOffCanvas" aria-controls="detailEventOffCanvas">
                    Ver últimas órdenes
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

@if( count($ordenes) > 0 )
<div class="offcanvas offcanvas-bottom" tabindex="-1" id="detailOrdenesOffCanvas" aria-labelledby="offcanvasBottomLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasBottomLabel">Últimas 10 órdenes de trabajo</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
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
            </tbody>
        </table>
  </div>
</div>
@endif

<form id="deleteForm" method="POST" action="{{ route('clientes.destroy', $client->id) }}">
@csrf
@method('DELETE')
</form>

<script>
    const deleteButton = document.getElementById("deleteButton");
    const deleteForm = document.getElementById("deleteForm");

    deleteButton.addEventListener("click", function() {
        @if ( count($vehiculos) > 0)
        document.getElementById("liveAlertVehiculos").style.display = "block";
        @else
        deleteForm.submit();
        @endif
    });

    @if ( count($vehiculos) > 0)
    document.getElementById("btnContinueSubmit").addEventListener("click", function() {
        deleteForm.submit();
    });

    document.getElementById("btnCancelSubmit").addEventListener("click", function() {
        document.getElementById("liveAlertVehiculos").style.display = "none";
    });
    @endif
    
    deleteForm.addEventListener("submit", function(e) {
        e.preventDefault();
    });
    

    const rutInput = document.getElementById("inputRut");
    rutInput.addEventListener("keydown", function(e) {
        e.target.value = e.target.value.replace(/[^0-9a-z]/gi, "");
    });
    rutInput.addEventListener("blur", function(e) {
        if(e.target.value){
            rutInput.value = `${e.target.value.substr(0, e.target.value.length - 1)}-${e.target.value.substr(-1, 1)}`;
        }
    });
    rutInput.addEventListener("focus", function(e) {
        e.target.value = e.target.value.replace(/[^0-9a-z]/gi, "");
    });
</script>

@endsection