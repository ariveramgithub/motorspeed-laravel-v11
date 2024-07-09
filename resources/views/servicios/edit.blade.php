@extends('layouts.app')

@section('content')

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{ url('/servicios') }}">Servicios</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edición</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fs-4 fw-semibold">{{ __('Editar Servicio') }}</div>
                <div class="card-body">
                <p class="card-text">{{ __('Completa todos los datos obligatorios (*) y haz click en "Guardar"') }}</p>
                <form method="POST" action="{{ route('servicios.update', $servicio->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="input-group">
                        <div class="form-floating mb-3 col-lg-12 col-md-6">
                            <input name="titulo" value="{{ $servicio->titulo }}" type="text" class="form-control @error('rut') is-invalid @enderror" id="inputTitulo" placeholder="" required >
                            @error('titulo')
                            <label for="inputTitulo">{{ $message }}</label>
                            @else
                            <label for="inputTitulo">Título (*)</label>
                            @enderror
                        </div>
                        <!-- <div class="form-floating mb-3 col-md-6">
                            <input name="valor" value="{{ $servicio->valor }}" type="text" class="form-control @error('valor') is-invalid @enderror" id="inputValor" placeholder="" required >
                            @error('valor')
                            <label for="inputValor">{{ $message }}</label>
                            @else
                            <label for="inputValor">Valor (*)</label>
                            @enderror
                        </div> -->
                    </div>
                    <div class="form-floating mb-3">
                        <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" placeholder="" id="inputDescripcion" style="height: 200px" required >{{ $servicio->descripcion }}</textarea>
                        @error('descripcion')
                        <label for="inputDescripcion">{{ $message }}</label>
                        @else
                        <label for="inputDescripcion">Descripción (*)</label>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-6 justify-content-start">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <a href="{{ route('servicios.index') }}" class="btn btn-secondary">Volver</a>
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
    </div>
</div>

<form id="deleteForm" method="POST" action="{{ route('servicios.destroy', $servicio->id) }}">
@csrf
@method('DELETE')
</form>

<script>
    const deleteButton = document.getElementById("deleteButton");
    const deleteForm = document.getElementById("deleteForm");

    deleteButton.addEventListener("click", function() {
        deleteForm.submit();
    });
</script>

@endsection