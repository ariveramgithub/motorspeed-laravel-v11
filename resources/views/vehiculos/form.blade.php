@extends('layouts.app')

@section('content')

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{ url('/vehiculos') }}">Vehículos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Nuevo</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fs-4 fw-semibold">{{ __('Ingresar nuevo Vehículo') }}</div>
                <div class="card-body">
                    <p class="card-text">{{ __('Completa todos los datos obligatorios (*) y haz click en "Guardar"') }}</p>
                    <form method="POST" action="{{ route('vehiculos.store') }}">
                    @csrf
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
                            <input name="patente" value="{{ old('patente') }}" type="text" class="form-control @error('patente') is-invalid @enderror" id="inputPatente" placeholder="" required >
                            @error('patente')
                            <label for="inputPatente">{{ $message }}</label>
                            @else
                            <label for="inputPatente">Patente (*)</label>
                            @enderror
                        </div>
                        <div class="form-floating mb-3 col-md-6">
                            <input name="marca" value="{{ old('marca') }}" type="text" class="form-control @error('marca') is-invalid @enderror" id="inputMarca" placeholder="" required >
                            @error('marca')
                            <label for="inputMarca">{{ $message }}</label>
                            @else
                            <label for="inputMarca">Marca (*)</label>
                            @enderror
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="form-floating mb-3 col-md-6">
                            <input name="modelo" value="{{ old('modelo') }}" type="text" class="form-control @error('modelo') is-invalid @enderror" id="inputModelo" placeholder="" required >
                            @error('modelo')
                            <label for="inputModelo">{{ $message }}</label>
                            @else
                            <label for="inputModelo">Modelo (*)</label>
                            @enderror
                        </div>
                        <div class="form-floating mb-3 col-md-6">
                            <input name="version" value="{{ old('version') }}" type="text" class="form-control @error('version') is-invalid @enderror" id="inputVersion" placeholder="" required >
                            @error('version')
                            <label for="inputVersion">{{ $message }}</label>
                            @else
                            <label for="inputVersion">Version (*)</label>
                            @enderror
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="form-floating mb-3 col-md-4">
                            <input name="color" value="{{ old('color') }}" type="text" class="form-control @error('color') is-invalid @enderror" id="inputColor" placeholder="" required >
                            @error('color')
                            <label for="inputColor">{{ $message }}</label>
                            @else
                            <label for="inpuinputColortModelo">Color (*)</label>
                            @enderror
                        </div>
                        <div class="form-floating mb-3 col-md-4">
                            <input name="year" value="{{ old('year') }}" type="number" class="form-control @error('year') is-invalid @enderror" id="inputYear" placeholder="" required >
                            @error('year')
                            <label for="inputYear">{{ $message }}</label>
                            @else
                            <label for="inputYear">Año (*)</label>
                            @enderror
                        </div>
                        <div class="form-floating mb-3 col-md-4">
                            <input name="kilometraje" value="{{ old('kilometraje') }}" type="number" class="form-control @error('kilometraje') is-invalid @enderror" id="inputKilometraje" placeholder="" required >
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
                                <option value="" @selected(old('transmision') == "")>Selecciona la opción</option>
                                <option value="mecanica" @selected(old('transmision') == 'mecanica')>Mecánica</option>
                                <option value="automatica" @selected(old('transmision') == 'automatica')>Automática</option>
                            </select>
                            @error('transmision')
                            <label for="inputTransmision">{{ $message }}</label>
                            @else
                            <label for="inputTransmision">Transmisión (*)</label>
                            @enderror
                        </div>
                        <div class="form-floating mb-3 col-md-6">
                            <select class="form-select @error('combustible') is-invalid @enderror" name="combustible" id="inputCombustible">
                                <option value="" @selected(old('combustible') == "")>Selecciona la opción</option>
                                <option value="gasolina" @selected(old('combustible') == "gasolina")>Gasolina</option>
                                <option value="diesel" @selected(old('combustible') == "diesel")>Diesel</option>
                                <option value="electrico" @selected(old('combustible') == "electrico")>Eléctrico</option>
                                <option value="hibrido" @selected(old('combustible') == "hibrido")>Híbrido</option>
                            </select>
                            @error('combustible')
                            <label for="inputCombustible">{{ $message }}</label>
                            @else
                            <label for="inputCombustible">Combustible (*)</label>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary">Volver</a>
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
    </div>
</div>

<script>
    const cliente_id = document.getElementById("inputClienteId");
    cliente_id.addEventListener(`focus`, () => cliente_id.select());
</script>

@endsection