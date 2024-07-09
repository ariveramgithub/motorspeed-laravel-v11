<div>
    <!-- It is quality rather than quantity that matters. - Lucius Annaeus Seneca -->
</div>
@extends('layouts.app')

@section('content')

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Servicios</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-1">
        <form method="POST" id="deleteForm" action="{{ route('servicios.destroy', 0)  }}">
        @csrf
        @method('DELETE')
        <input type="hidden" name="deleteIds" id="deleteIds" value="">
            <div class="input-group">
                <button id="trashButton" class="btn btn-primary" disabled aria-label="Delete" title="Borrar" onClick="trashButtonClick()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                        <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                    </svg>
                </button>
            </div>
        </form>
        </div>
        <div class="col-md-3 offset-md-7">
        <form method="GET" action="{{ route('servicios.index') }}">
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
                <a href="{{ url('/servicios/create') }}" class="btn btn-primary" aria-label="Add" title="Agregar">
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
                    <th scope="col"><input type="checkbox" id="multipleSelectionToggle" onClick="toggleCheckbox()" aria-label="Multiple selected Checkbox"></th>
                    <th scope="col">Título</th>
                    <th scope="col">Descripción</th>
                    <!--<th scope="col">Valor</th>-->
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($servicios as $servicio)
                <tr>
                    <th scope="row">{{ $servicio->id }}</th>
                    <td><input type="checkbox" name="deleteId[]" value="{{ $servicio->id }}" aria-label="Select ID {{ $servicio->id }}"></td>
                    <td>{{ $servicio->titulo }}</td>
                    <td title="{{ $servicio->descripcion }}">{{ (strlen($servicio->descripcion) > 70) ? substr($servicio->descripcion, 0, 70).'...' : $servicio->descripcion }}</td>
                    <!--<td>{{ ($servicio->valor) > 0 ? '$'.number_format($servicio->valor, 0, ',', '.') : 0 }}</td>-->
                    <td>
                        <a href="{{ url('/servicios').'/'.$servicio->id.'/edit' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
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
                    <td></td>
                    <td></td>
                    <td></td>
                    <!--<td></td>-->
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
                ])><a class="page-link" href="/servicios?page={{ $i }}{{ ($querystring != '') ? '&query=' . $querystring : '' }}">{{ $i }}</a></li>
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

<script>

const checkboxs = document.querySelectorAll("input[type=checkbox]");
const trashButton = document.getElementById("trashButton");
trashButton.disabled = true;

const toggleCheckbox = (checkboxId) => {
    const multipleCheckbox = document.getElementById("multipleSelectionToggle");
    const deletedIdCheckbox = document.getElementsByName("deleteId[]");

    for(elem in deletedIdCheckbox) {
        deletedIdCheckbox[elem].checked = multipleCheckbox.checked;
    }
}

const trashButtonToggle = () => {
    trashButton.disabled = true;

    for(elem in checkboxs) {
        if( checkboxs[elem].checked && !Number.isNaN(parseInt(checkboxs[elem].value)) && typeof parseInt(checkboxs[elem].value) === "number" ) {
            trashButton.disabled = false;
        }
    }

    if( trashButton.disabled ){
        const multipleCheckbox = document.getElementById("multipleSelectionToggle");
        multipleCheckbox.checked = false;
    }
}
const trashButtonClick = () => {

    const deleteIds = document.getElementById("deleteIds");
    const arrayDeleteId = [];

    for(elem in checkboxs) {
        if( checkboxs[elem].checked && !Number.isNaN(parseInt(checkboxs[elem].value)) && typeof parseInt(checkboxs[elem].value) === "number" ) {
            arrayDeleteId.push(parseInt(checkboxs[elem].value));
        }
    }

    deleteIds.value = arrayDeleteId;
    const deleteForm = document.getElementById("deleteForm");
    deleteForm.submit();
}

for(elem in checkboxs) {
    if(checkboxs[elem].type === "checkbox") {
        checkboxs[elem].addEventListener("click", function() {
            trashButtonToggle();
        });
    }
}
</script>

@endsection