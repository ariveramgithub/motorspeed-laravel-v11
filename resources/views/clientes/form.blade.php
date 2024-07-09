@extends('layouts.app')

@section('content')

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{ url('/clientes') }}">Clientes</a></li>
            <li class="breadcrumb-item active" aria-current="page">Nuevo</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fs-4 fw-semibold">{{ __('Ingresar nuevo Cliente') }}</div>
                <div class="card-body">
                <p class="card-text">{{ __('Completa todos los datos obligatorios (*) y haz click en "Guardar"') }}</p>
                <form method="POST" action="{{ route('clientes.store') }}">
                    @csrf
                    <div class="input-group">
                        <div class="form-floating mb-3 col-md-6">
                            <input name="rut" value="{{ old('rut') }}" type="text" class="form-control @error('rut') is-invalid @enderror" id="inputRut" placeholder="" required >
                            @error('rut')
                            <label for="inputRut">{{ $message }}</label>
                            @else
                            <label for="inputRut">RUT (*) (sin puntos y con guión)</label>
                            @enderror
                        </div>
                        <div class="form-floating mb-3 col-md-6">
                            <input name="nombre" value="{{ old('nombre') }}" type="text" class="form-control @error('nombre') is-invalid @enderror" id="inputNombre" placeholder="" required >
                            @error('nombre')
                            <label for="inputNombre">{{ $message }}</label>
                            @else
                            <label for="inputNombre">Nombre (*)</label>
                            @enderror
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <input name="direccion" value="{{ old('direccion') }}" type="text" class="form-control @error('direccion') is-invalid @enderror" id="inputDireccion" placeholder="" required >
                        @error('direccion')
                        <label for="inputDireccion">{{ $message }}</label>
                        @else
                        <label for="inputDireccion">Dirección (*)</label>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input name="email" value="{{ old('email') }}" type="text" class="form-control @error('email') is-invalid @enderror" id="inputEmail" placeholder="" required >
                        @error('email')
                        <label for="inputEmail">{{ $message }}</label>
                        @else
                        <label for="inputEmail">Email address (*)</label>
                        @enderror
                    </div>
                    <div class="input-group">
                        <div class="form-floating mb-3 col-md-6">
                            <input name="telefono1" value="{{ old('telefono1') }}" type="text" class="form-control @error('telefono1') is-invalid @enderror" id="inputTelefono1" placeholder="" required >
                            @error('telefono1')
                            <label for="inputTelefono1">{{ $message }}</label>
                            @else
                            <label for="inputTelefono1">Teléfono (*)</label>
                            @enderror
                        </div>
                        <div class="form-floating mb-3 col-md-6">
                            <input name="telefono2" value="{{ old('telefono2') }}" type="text" class="form-control" id="inputTelefono2" placeholder="">
                            <label for="inputTelefono2">Otro teléfono</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 justify-content-start">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Volver</a>
                        </div>
                    </div>
                </form>
                </div>
            </div>
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                @if (session('newClient'))
                <a href="{{ route('vehiculos.create').'?client='.session('newClient') }}" class="btn btn-primary">Agregar Vehículo</a>
                @endif
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
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