<?php

namespace App\Http\Controllers\Clientes;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Rules\Rut;
use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Models\OrdenTrabajo;

class ClientesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $querystring = $request->string('query')->trim();

        if($querystring != "") {
            $clientes = Cliente::where('rut', 'like', '%'.$querystring.'%')
            ->orWhere('nombre', 'like', '%'.$querystring.'%')
            ->orWhere('direccion', 'like', '%'.$querystring.'%')
            ->orWhere('email', 'like', '%'.$querystring.'%')
            ->orWhere('telefono1', 'like', '%'.$querystring.'%')
            ->paginate(10);
        }
        else {
            $clientes = Cliente::paginate(10);
        }

        $data = [
            'clientes' => $clientes,
            'countItems' => $clientes->count(),
            'currentPage' => $clientes->currentPage(),
            'previousPageUrl' => $clientes->previousPageUrl() . ( ($clientes->previousPageUrl() && $querystring != "") ? "&query=" . $querystring : "" ),
            'nextPageUrl' => $clientes->nextPageUrl() . ( ($clientes->nextPageUrl() && $querystring != "") ? "&query=" . $querystring : "" ),
            'lastPage' => $clientes->lastPage(),
            'perPage' => $clientes->perPage(),
            'querystring' => $querystring,
        ];

        return view('clientes/index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clientes/form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Primero valida si RUT ya existe en estado "borrado"
        $deletedClient = Cliente::onlyTrashed()
                                ->where('rut', $request->rut)
                                ->get();
        if( count($deletedClient) > 0 ) {
            // Si cliente existe en estado "borrado", se borra definitivamente de la tabla para ser creado nuevamente.
            $deletedClient[0]->forceDelete();
        }

        $validator = Validator::make($request->all(), [
                'rut' => ['bail', 'required', 'unique:clientes', 'max:10', new Rut],
                'nombre' => 'required|max:255',
                'direccion' => 'required|max:255',
                'email' => 'required|unique:clientes|email|max:255',
                'telefono1' => 'required|max:20',
                'telefono2' => 'max:20',
            ],
            $messages = [
                'required' => 'El campo :attribute es requerido.',
                'max' => 'El campo :attribute no debe tener más de :max caracteres.',
                'rut.unique' => 'El campo :attribute ya se encuentra ingresado.',
                'direccion.required' => 'El campo dirección es requerido.',
                'direccion.max' => 'El campo dirección no debe tener más de :max caracteres.',
                'email.unique' => 'El campo :attribute ya se encuentra ingresado.',
                'email.email' => 'El campo :attribute debe ser un correo electrónico válido.',
                'telefono1.required' => 'El campo teléfono es requerido.',
                'telefono1.max' => 'El campo teléfono no debe tener más de :max caracteres.',
                'telefono2.max' => 'El campo otro teléfono no debe tener más de :max caracteres.',
        ]);

        if ($validator->fails()) {
            return redirect('/clientes/create')
                        ->withErrors($validator)
                        ->withInput();
        }

        $validated = $validator->validated();

        $client = Cliente::create(
            [
                'rut' => $request->rut,
                'nombre' => $request->nombre,
                'direccion' => $request->direccion,
                'email' => $request->email,
                'telefono1' => $request->telefono1,
                'telefono2' => $request->telefono2,
            ]
        );

        return redirect('/clientes/create')->with([
            'success' => "Registro creado exitosamente.",
            'newClient' => $client->id,
        ]);
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
        $client = Cliente::find($id);
        $client->rut = $request->old('rut') ? $request->old('rut') : $client->rut;
        $client->nombre = $request->old('nombre') ? $request->old('nombre') : $client->nombre;
        $client->direccion = $request->old('direccion') ? $request->old('direccion') : $client->direccion;
        $client->email = $request->old('email') ? $request->old('email') : $client->email;
        $client->telefono1 = $request->old('telefono1') ? $request->old('telefono1') : $client->telefono1;
        $client->telefono2 = $request->old('telefono2') ? $request->old('telefono2') : $client->telefono2;

        $vehiculos = Vehiculo::where("cliente_id", $id)->get();
        $ordenes = OrdenTrabajo::where("cliente_rut", $client->rut)->orderBy("inicio", "desc")->limit(10)->get();

        if(count($ordenes)){
            foreach($ordenes as $k => $row){
                $ordenes[$k]->inicio = $row->inicio ? date("d/m/Y", strtotime($row->inicio)) : $row->inicio;
                $ordenes[$k]->termino = $row->termino ? date("d/m/Y", strtotime($row->termino)) : $row->termino;
            }
        }

        $data = [
            'client' => $client,
            'vehiculos' => $vehiculos,
            'ordenes' => $ordenes,
        ];

        return view('clientes/edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $client = Cliente::find($id);

        $rules = [
            'nombre' => 'required|max:255',
            'direccion' => 'required|max:255',
            'telefono1' => 'required|max:20',
            'telefono2' => 'max:20',
            'rut' => ['bail', 'required', 'max:10', new Rut],
            'email' => ['required', 'email', 'max:255'],
        ];

        if( $client->rut != $request->rut ){
            array_push($rules['rut'], 'unique:clientes'); 
        }

        if( $client->email != $request->email ){
            array_push($rules['email'], 'unique:clientes'); 
        } 


        $validator = Validator::make($request->all(),
        $rules,
        $messages = [
            'required' => 'El campo :attribute es requerido.',
            'max' => 'El campo :attribute no debe tener más de :max caracteres.',
            'rut.unique' => 'El campo :attribute ya se encuentra ingresado.',
            'direccion.required' => 'El campo :attribute es requerido.',
            'direccion.max' => 'El campo :attribute no debe tener más de :max caracteres.',
            'email.unique' => 'El campo :attribute ya se encuentra ingresado.',
            'email.email' => 'El campo :attribute debe ser un correo electrónico válido.',
            'telefono1.required' => 'El campo :attribute es requerido.',
            'telefono1.max' => 'El campo :attribute no debe tener más de :max caracteres.',
            'telefono2.max' => 'El campo :attribute no debe tener más de :max caracteres.',
        ]);

        if ($validator->fails()) {
            return redirect('/clientes/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }

        $validated = $validator->validated();

        
        $client->rut = $request->rut;
        $client->nombre = $request->nombre;
        $client->direccion = $request->direccion;
        $client->email = $request->email;
        $client->telefono1 = $request->telefono1;
        $client->telefono2 = $request->telefono2;
        $client->save();

        return redirect('/clientes/'.$id.'/edit')->with('success', "Registro actualizado exitosamente.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        // Eliminación múltiple
        if((int)$id === 0 && $request->deleteIds && count(explode(",", $request->deleteIds)) > 0) {
            
            $deleteIds = explode(",", $request->deleteIds);
            Cliente::destroy($deleteIds);

            foreach($deleteIds as $clienteId){
                Vehiculo::where('cliente_id', $clienteId)->update(['cliente_id' => null]);
            }

            return redirect('/clientes')->with('success', "Registros eliminados exitosamente.");
        }
        else {
            $cliente = Cliente::find($id);
            Cliente::destroy($id);

            Vehiculo::where('cliente_id', $id)->update(['cliente_id' => null]);

            return redirect('/clientes')->with('success', "Registro [".$cliente->rut."] eliminado exitosamente.");
        }
    }
}
