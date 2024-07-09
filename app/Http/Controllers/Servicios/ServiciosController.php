<?php

namespace App\Http\Controllers\Servicios;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Servicio;

class ServiciosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $querystring = $request->string('query')->trim();

        if($querystring != "") {
            $servicios = Servicio::where('titulo', 'like', '%'.$querystring.'%')
            ->orWhere('valor', 'like', '%'.$querystring.'%')
            ->paginate(10);
        }
        else {
            $servicios = Servicio::paginate(10);
        }

        $data = [
            'servicios' => $servicios,
            'countItems' => $servicios->count(),
            'currentPage' => $servicios->currentPage(),
            'previousPageUrl' => $servicios->previousPageUrl() . ( ($servicios->previousPageUrl() && $querystring != "") ? "&query=" . $querystring : "" ),
            'nextPageUrl' => $servicios->nextPageUrl() . ( ($servicios->nextPageUrl() && $querystring != "") ? "&query=" . $querystring : "" ),
            'lastPage' => $servicios->lastPage(),
            'perPage' => $servicios->perPage(),
            'querystring' => $querystring,
        ];

        return view('servicios/index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('servicios/form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|max:255',
            'valor' => 'numeric|max:999999999',
            'descripcion' => 'required',
        ],
        $messages = [
            'required' => 'El campo :attribute es requerido.',
            'max' => 'El campo :attribute no debe tener más de :max caracteres.',
            'numeric' => 'El campo :attribute debe ser numérico.',
            'titulo.required' => 'El campo título es requerido.',
            'titulo.max' => 'El campo título no debe tener más de :max caracteres.',
            'descripcion.required' => 'El campo descripción es requerido.',
            'valor.max' => 'El campo :attribute no debe ser mayor a :max.',
        ]);

        if ($validator->fails()) {
            return redirect('/servicios/create')
                        ->withErrors($validator)
                        ->withInput();
        }

        $validated = $validator->validated();

        Servicio::create(
            [
                'titulo' => $request->titulo,
                'valor' => 0, //$request->valor,
                'descripcion' => $request->descripcion,
            ]
        );

        return redirect('/servicios/create')->with('success', "Registro creado exitosamente.");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        $servicio = Servicio::find($id);
        $servicio->titulo = $request->old('titulo') ? $request->old('titulo') : $servicio->titulo;
        //$servicio->valor = $request->old('valor') ? $request->old('valor') : $servicio->valor;
        $servicio->descripcion = $request->old('descripcion') ? $request->old('descripcion') : $servicio->descripcion;

        $data = [
            'servicio' => $servicio
        ];

        return view('servicios/edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $servicio = Servicio::find($id);

        $rules = [
            'titulo' => 'required|max:255',
            'valor' => 'numeric|max:999999999',
            'descripcion' => 'required',
        ];

        $validator = Validator::make($request->all(),
        $rules,
        $messages = [
            'required' => 'El campo :attribute es requerido.',
            'max' => 'El campo :attribute no debe tener más de :max caracteres.',
            'numeric' => 'El campo :attribute debe ser numérico.',
            'titulo.required' => 'El campo título es requerido.',
            'titulo.max' => 'El campo título no debe tener más de :max caracteres.',
            'descripcion.required' => 'El campo descripción es requerido.',
            'valor.max' => 'El campo :attribute no debe ser mayor a :max.',
        ]);

        if ($validator->fails()) {
            return redirect('/servicios/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }

        $validated = $validator->validated();

        
        $servicio->titulo = $request->titulo;
        $servicio->valor = 0; //$request->valor;
        $servicio->descripcion = $request->descripcion;
        $servicio->save();

        return redirect('/servicios/'.$id.'/edit')->with('success', "Registro actualizado exitosamente.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        // Eliminación múltiple
        if((int)$id === 0 && $request->deleteIds && count(explode(",", $request->deleteIds)) > 0) {
            
            $deleteIds = explode(",", $request->deleteIds);
            Servicio::destroy($deleteIds);

            return redirect('/servicios')->with('success', "Registros eliminados exitosamente.");
        }
        else {
            $servicio = Servicio::find($id);
            Servicio::destroy($id);

            return redirect('/servicios')->with('success', "Registro [".$servicio->titulo."] eliminado exitosamente.");
        }
    }
}
