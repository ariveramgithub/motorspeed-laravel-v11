@extends('layouts.app')

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endpush

@section('content')

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{ url('/ordentrabajos') }}">Órdenes de trabajo</a></li>
            <li class="breadcrumb-item active" aria-current="page">Nuevo</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fs-4 fw-semibold">{{ __('Ingresar nueva órden de trabajo') }}</div>
                <div class="card-body">
                    <p class="card-text">{{ __('Completa todos los datos obligatorios (*) y haz click en "Guardar"') }}</p>
                    <form method="POST" action="{{ route('ordentrabajos.store') }}">
                    @csrf
                    <input type="hidden" id="servicios" name="servicios" value=""> 
                    <p class="fw-bold">Datos cliente y vehículo</p>
                    <div class="input-group">
                        <div class="form-floating mb-3 col-md-6">
                            <input name="vehiculo_id" value="{{ old('vehiculo_id') }}" class="form-control @error('vehiculo_id') is-invalid @enderror" list="vehiculoslistOptions" id="inputVehiculoId" placeholder="Type to search..." required >
                            <datalist id="vehiculoslistOptions">
                                <option value=""></option>
                                @foreach ($vehiculos as $vehiculo)
                                <option value="{{ $vehiculo->patente }}, {{ $vehiculo->marca }} {{ $vehiculo->modelo }}"></option>
                                @endforeach
                            </datalist>
                            @error('vehiculo_id')
                            <label for="inputVehiculoId">{{ $message }}</label>
                            @else
                            <label for="inputVehiculoId">Vehículo (*)</label>
                            @enderror
                        </div>
                        <div class="form-floating mb-3 col-md-6">
                            <input name="cliente_id" value="{{ old('cliente_id') }}" class="form-control @error('cliente_id') is-invalid @enderror" list="clienteslistOptions" id="inputClienteId" placeholder="Type to search..." required >
                            <datalist id="clienteslistOptions">
                                <option value=""></option>
                                @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->rut }}, {{ $cliente->nombre }}"></option>
                                @endforeach
                            </datalist>
                            @error('cliente_id')
                            <label for="inputClienteId">{{ $message }}</label>
                            @else
                            <label for="inputClienteId">Cliente (*)</label>
                            @enderror
                        </div>
                    </div>
                    <p class="fw-bold">Selecciona el o los servicios asociados</p>
                    <div class="input-group">
                        <div class="form-floating mb-3 col-md-6">
                            <select class="form-select @error('servicio_id') is-invalid @enderror" name="servicio_id" id="selectServicioId">
                                <option value="" @selected(old('servicio_id') == "")>Seleccione</option>
                                @foreach ($servicios as $servicio)
                                <option value="{{ $servicio->id }}" @selected(old('servicio_id') == $servicio->id)>{{ $servicio->titulo }}</option>
                                @endforeach
                            </select>
                            @error('servicio_id')
                            <label for="selectServicioId">{{ $message }}</label>
                            @else
                            <label for="selectServicioId">Servicio</label>
                            @enderror
                        </div>
                    </div>
                    <div id="serviciosList" class="d-flex gap-2 justify-content-center pb-3"></div>
                    <p class="fw-bold">Completa datos faltantes</p>
                    <div class="input-group">
                        <div class="form-floating mb-3 col-md-4">
                            <input name="inicio" value="{{ old('inicio') }}" type="datetime-local" class="form-control @error('inicio') is-invalid @enderror" id="inputInicio" placeholder="" required >
                            @error('inicio')
                            <label for="inputInicio">{{ $message }}</label>
                            @else
                            <label for="inputInicio">Inicio servicio (*)</label>
                            @enderror
                        </div>
                        <div class="form-floating mb-3 col-md-4">
                            <input name="termino" value="{{ old('termino') }}" type="datetime-local" class="form-control @error('termino') is-invalid @enderror" id="inputTermino" placeholder="" required >
                            @error('termino')
                            <label for="inputTermino">{{ $message }}</label>
                            @else
                            <label for="inputTermino">Término servicio (*)</label>
                            @enderror
                        </div>
                        <div class="form-floating mb-3 col-md-4">
                            <input name="valor" value="{{ old('valor') }}" type="number" class="form-control @error('valor') is-invalid @enderror" id="inputValor" placeholder="" required >
                            @error('valor')
                            <label for="inputValor">{{ $message }}</label>
                            @else
                            <label for="inputValor">Valor final servicio (*)</label>
                            @enderror
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea name="detalle_cliente" class="form-control @error('detalle_cliente') is-invalid @enderror" placeholder="" id="inputDetalleCliente" style="height: 200px">{{ old('detalle_cliente') }}</textarea>
                        @error('detalle_cliente')
                        <label for="inputDetalleCliente">{{ $message }}</label>
                        @else
                        <label for="inputDetalleCliente">Observación Cliente</label>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <textarea name="detalle_taller" class="form-control @error('detalle_taller') is-invalid @enderror" placeholder="" id="inputDetalleTaller" style="height: 200px">{{ old('detalle_taller') }}</textarea>
                        @error('detalle_taller')
                        <label for="inputDetalleTaller">{{ $message }}</label>
                        @else
                        <label for="inputDetalleTaller">Observación Taller</label>
                        @enderror
                    </div>
                    <p class="fw-bold">Selecciona estado (*)</p>
                    <div class="mb-3 d-flex justify-content-center">
                        @foreach ($estados as $estado)
                        <input type="radio" class="btn-check" name="estado" value="{{ $estado->estado }}" id="estadoOption{{ $estado->id }}" autocomplete="off" @checked($estado->estado == "En espera")>
                        <label class="btn btn-outline-primary m-2" for="estadoOption{{ $estado->id }}">{{ $estado->estado }}</label>
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="{{ route('ordentrabajos.index') }}" class="btn btn-secondary">Volver</a>
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
    $(document).ready(function () {
        const SITEURL = "{{ url('/ordentrabajos') }}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#inputClienteId").on('focus', function(){
            $(this).select();
        });

        // Select vehiculo -> cliente
        $("#inputVehiculoId").on("change", function(){
            const vehiculoId = $(this).val();
            $.ajax({
                    url: SITEURL + '/getCliente',
                    data: {
                        vehiculo_id: vehiculoId,
                    },
                    type: "POST",
                    success: function (response) {
                        const clienteslistOptions = $("#clienteslistOptions");
                        $("#inputClienteId").val('');
                        clienteslistOptions.html('');
                        if(response.length > 0){
                            clienteslistOptions.append('<option value=""></option>');

                            for(row in response){
                                clienteslistOptions.append(`<option value="${response[row].rut}, ${response[row].nombre}"></option>`);
                            }
                        } else {
                            clienteslistOptions.append('<option value=""></option>');
                        }
                    }
                });
        }).on('focus', function(){
            $(this).select();
        });

        // Funciones para servicios
        $("#selectServicioId").on("change", function(){
            const input = $(this);
            const servicios = ( $("#servicios").val() ) ? $("#servicios").val().split("|") : [];
            const text = $("#selectServicioId option:selected").text();

            if( input.val() && $("#servicios").val().indexOf( text ) === -1 ){
                
                servicios.push(text);

                $("#serviciosList").append(
                    `<span class="badge rounded-pill text-bg-primary p-2">
                        <span>${text}</span>
                        <a data-id="${text}" class="deleteServicio" href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white" class="bi bi-x-circle-fill ms-1" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                            </svg>
                        </a>
                    </span>`
                );
            }

            $("#servicios").val(servicios.join("|"));
            input.prop("selectedIndex", 0);
        });
        $("#serviciosList").on("click", ".deleteServicio", function(){
            const id = $(this).data("id");
            
            if( $("#servicios").val() ){
                const servicios = $("#servicios").val().split("|");
                const index = servicios.findIndex(e => e === id );
                servicios.splice( index, 1 );

                if( servicios.length > 0 ){
                    $("#servicios").val(servicios.join("|"));
                } else {
                    $("#servicios").val("");
                }

                $(this).parent().remove()
            }
        });
    });
</script>
@endsection