@extends('layouts.app')

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endpush

@section('content')

<style>
#detailOrdenesOffCanvas {
    --bs-offcanvas-height: 80vh;
}
</style>

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{ url('/vehiculos') }}">Vehículos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edición</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="offset-md-2 col-md-8">
            <div class="card">
                <div class="card-header fs-4 fw-semibold">{{ __('Editar Vehículo') }}</div>
                <div class="card-body">
                <p class="card-text">{{ __('Completa todos los datos obligatorios (*) y haz click en "Guardar"') }}</p>
                <form method="POST" action="{{ route('vehiculos.update', $vehiculo->id) }}">
                    @csrf
                    @method('PUT')
                    <p class="fw-bold">Datos Cliente</p>
                    <div class="input-group">
                        <div class="form-floating mb-3 col-md-6">
                            <input name="cliente_id" value="{{ ( old('cliente_id') ) ? old('cliente_id') : $defaultClient }}" class="form-control @error('cliente_id') is-invalid @enderror" list="clienteslistOptions" id="inputClienteId" placeholder="Type to search..." required >
                            <datalist id="clienteslistOptions">
                                <option value="Seleccione"></option>
                                @foreach ($clients as $client)
                                <option value="{{ $client->rut }}, {{ $client->nombre }}"></option>
                                @endforeach
                            </datalist>
                            @error('cliente_id')
                            <label for="inputClienteId">{{ $message }}</label>
                            @else
                            <label for="inputClienteId">Cliente (*)</label>
                            @enderror
                        </div>
                    </div>
                    <p class="fw-bold">Datos Vehículo</p>
                    <div class="input-group">
                        <div class="form-floating mb-3 col-md-6">
                            <input name="patente" value="{{ $vehiculo->patente }}" type="text" class="form-control @error('patente') is-invalid @enderror" id="inputPatente" placeholder="" required >
                            @error('patente')
                            <label for="inputPatente">{{ $message }}</label>
                            @else
                            <label for="inputPatente">Patente (*)</label>
                            @enderror
                        </div>
                        <div class="form-floating mb-3 col-md-6">
                            <input name="marca" value="{{ $vehiculo->marca }}" type="text" class="form-control @error('marca') is-invalid @enderror" id="inputMarca" placeholder="" required >
                            @error('marca')
                            <label for="inputMarca">{{ $message }}</label>
                            @else
                            <label for="inputMarca">Marca (*)</label>
                            @enderror
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="form-floating mb-3 col-md-6">
                            <input name="modelo" value="{{ $vehiculo->modelo }}" type="text" class="form-control @error('modelo') is-invalid @enderror" id="inputModelo" placeholder="" required >
                            @error('modelo')
                            <label for="inputModelo">{{ $message }}</label>
                            @else
                            <label for="inputModelo">Modelo (*)</label>
                            @enderror
                        </div>
                        <div class="form-floating mb-3 col-md-6">
                            <input name="version" value="{{ $vehiculo->version }}" type="text" class="form-control @error('version') is-invalid @enderror" id="inputVersion" placeholder="" required >
                            @error('version')
                            <label for="inputVersion">{{ $message }}</label>
                            @else
                            <label for="inputVersion">Version (*)</label>
                            @enderror
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="form-floating mb-3 col-md-4">
                            <input name="color" value="{{ $vehiculo->color }}" type="text" class="form-control @error('color') is-invalid @enderror" id="inputColor" placeholder="" required >
                            @error('color')
                            <label for="inputColor">{{ $message }}</label>
                            @else
                            <label for="inpuinputColortModelo">Color (*)</label>
                            @enderror
                        </div>
                        <div class="form-floating mb-3 col-md-4">
                            <input name="year" value="{{ $vehiculo->year }}" type="number" class="form-control @error('year') is-invalid @enderror" id="inputYear" placeholder="" required >
                            @error('year')
                            <label for="inputYear">{{ $message }}</label>
                            @else
                            <label for="inputYear">Año (*)</label>
                            @enderror
                        </div>
                        <div class="form-floating mb-3 col-md-4">
                            <input name="kilometraje" value="{{ $vehiculo->kilometraje }}" type="number" class="form-control @error('kilometraje') is-invalid @enderror" id="inputKilometraje" placeholder="" required >
                            @error('kilometraje')
                            <label for="inputKilometraje">{{ $message }}</label>
                            @else
                            <label for="inputKilometraje">Kilometraje (*)</label>
                            @enderror
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="form-floating mb-3 col-md-6">
                            <select class="form-select @error('transmision') is-invalid @enderror" name="transmision" id="inputTransmision">
                                <option value="" @selected($vehiculo->transmision == "")>Selecciona la opción</option>
                                <option value="mecanica" @selected($vehiculo->transmision == 'mecanica')>Mecánica</option>
                                <option value="automatica" @selected($vehiculo->transmision == 'automatica')>Automática</option>
                            </select>
                            @error('transmision')
                            <label for="inputTransmision">{{ $message }}</label>
                            @else
                            <label for="inputTransmision">Transmisión (*)</label>
                            @enderror
                        </div>
                        <div class="form-floating mb-3 col-md-6">
                            <select class="form-select @error('combustible') is-invalid @enderror" name="combustible" id="inputCombustible">
                                <option value="" @selected($vehiculo->combustible == "")>Selecciona la opción</option>
                                <option value="gasolina" @selected($vehiculo->combustible == "gasolina")>Gasolina</option>
                                <option value="diesel" @selected($vehiculo->combustible == "diesel")>Diesel</option>
                                <option value="electrico" @selected($vehiculo->combustible == "electrico")>Eléctrico</option>
                                <option value="hibrido" @selected($vehiculo->combustible == "hibrido")>Híbrido</option>
                            </select>
                            @error('combustible')
                            <label for="inputCombustible">{{ $message }}</label>
                            @else
                            <label for="inputCombustible">Combustible (*)</label>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 justify-content-start">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary">Volver</a>
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <button id="deleteButton" type="button" class="btn btn-warning">Eliminar</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
        </div>
        <div class="col-md-2">
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

<form id="deleteForm" method="POST" action="{{ route('vehiculos.destroy', $vehiculo->id) }}">
@csrf
@method('DELETE')
</form>

<script>
    const deleteButton = document.getElementById("deleteButton");
    const deleteForm = document.getElementById("deleteForm");
    const cliente_id = document.getElementById("inputClienteId");

    deleteButton.addEventListener("click", function() {
        deleteForm.submit();
    });

    
    cliente_id.addEventListener(`focus`, () => cliente_id.select());
</script>

@endsection