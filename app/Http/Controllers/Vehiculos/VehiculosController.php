<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Vehiculo;
use App\Models\Cliente;
use App\Models\OrdenTrabajo;

class VehiculosController extends Controller
{

    private const ENUMLABELS = [
        '' => '',
        'automatica' => 'Automática',
        'mecanica' => 'Mecánica',
        'gasolina' => 'Gasolina',
        'diesel' => 'Diesel',
        'electrico' => 'Eléctrico',
    ];

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
            $vehiculos = Vehiculo::where('patente', 'like', '%'.$querystring.'%')
            ->orWhere('marca', 'like', '%'.$querystring.'%')
            ->orWhere('modelo', 'like', '%'.$querystring.'%')
            ->orWhere('color', 'like', '%'.$querystring.'%')
            ->orWhere('year', 'like', '%'.$querystring.'%')
            ->orWhere('transmision', 'like', '%'.$querystring.'%')
            ->orWhere('combustible', 'like', '%'.$querystring.'%')
            ->paginate(10);
        }
        else {
            $vehiculos = Vehiculo::paginate(10);
        }

        if($vehiculos){
            foreach($vehiculos as $row){
                if($row->cliente_id){
                    $cliente = Cliente::find($row->cliente_id);
                    $row->cliente_nombre = $cliente->nombre;
                }
                else{
                    $row->cliente_nombre = null;
                }
            }
        }

        $data = [
            'vehiculos' => $vehiculos,
            'countItems' => $vehiculos->count(),
            'currentPage' => $vehiculos->currentPage(),
            'previousPageUrl' => $vehiculos->previousPageUrl() . ( ($vehiculos->previousPageUrl() && $querystring != "") ? "&query=" . $querystring : "" ),
            'nextPageUrl' => $vehiculos->nextPageUrl() . ( ($vehiculos->nextPageUrl() && $querystring != "") ? "&query=" . $querystring : "" ),
            'lastPage' => $vehiculos->lastPage(),
            'perPage' => $vehiculos->perPage(),
            'querystring' => $querystring,
            'enumLabels' => self::ENUMLABELS,
        ];

        return view('vehiculos/index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $clients = Cliente::all();
        $cliente = Cliente::find($request->string('client'));

        if( $cliente ){
            $defaultClient = Cliente::find($cliente->id);
            $defaultClient = $defaultClient->rut.', '.$defaultClient->nombre;
        } else {
            $defaultClient = null;
        }

        $data = [
            'clients' => $clients,
            'defaultClient' => $defaultClient,
        ];
        return view('vehiculos/form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Primero valida si patente ya existe en estado "borrado"
        $deletedVehiculo = Vehiculo::onlyTrashed()
                                ->where('patente', $request->patente)
                                ->get();
        if( count($deletedVehiculo) > 0 ) {
            // Si vehículo existe en estado "borrado", se borra definitivamente de la tabla para ser creado nuevamente.
            $deletedVehiculo[0]->forceDelete();
        }

        // Pasamos datos de request a variable requestData, la que será validada por Validator Class
        $requestData = $request->all();
        $rut = trim(explode(",", $request->cliente_id)[0]);
        $getCliente = Cliente::where("rut", $rut)->get();

        // Si se encuentra cliente por el rut obtenido entonces seteamos cliente_id
        if( count($getCliente) > 0 ){
            $requestData['cliente_id'] = $getCliente[0]->id;
            $request->cliente_id = $getCliente[0]->id;
        } else { // De lo contrario es nulo
            $requestData['cliente_id'] = null;
        }

        $validator = Validator::make($requestData, [
            'patente' => ['required', 'unique:vehiculos', 'max:6'],
            'marca' => 'required|max:255',
            'modelo' => 'required|max:255',
            'version' => 'required|max:255',
            'color' => 'required|max:20',
            'year' => 'required|numeric',
            'kilometraje' => 'required|numeric',
            'transmision' => 'required',
            'combustible' => 'required',
            'cliente_id' => 'required',
        ],
        $messages = [
            'required' => 'El campo :attribute es requerido.',
            'max' => 'El campo :attribute no debe tener más de :max caracteres.',
            'cliente_id.required' => 'El campo Cliente es requerido. Selecciona un valor de la lista.',
            'patente.unique' => 'El campo :attribute ya se encuentra registrado',
            'version.required' => 'El campo :attribute es requerido.',
            'version.max' => 'El campo :attribute no debe tener más de :max caracteres.',
            'year.required' => 'El campo año es requerido.',
            'year.numeric' => 'El campo año debe ser numérico.',
            'transmision.required' => 'El campo transmisión es requerido.',
        ]);

        if ($validator->fails()) {
            return redirect('/vehiculos/create')
                        ->withErrors($validator)
                        ->withInput();
        }

        $validated = $validator->validated();

        Vehiculo::create(
            [
                'patente' => $request->patente,
                'marca' => $request->marca,
                'modelo' => $request->modelo,
                'version' => $request->version,
                'color' => $request->color,
                'year' => $request->year,
                'kilometraje' => $request->kilometraje,
                'transmision' => $request->transmision,
                'combustible' => $request->combustible,
                'cliente_id' => $request->cliente_id,
            ]
        );

        return redirect('/vehiculos/create')->with('success', "Registro creado exitosamente.");
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
        $vehiculo = Vehiculo::find($id);
        $vehiculo->patente = $request->old('patente') ? $request->old('patente') : $vehiculo->patente;
        $vehiculo->marca = $request->old('marca') ? $request->old('marca') : $vehiculo->marca;
        $vehiculo->modelo = $request->old('modelo') ? $request->old('modelo') : $vehiculo->modelo;
        $vehiculo->version = $request->old('version') ? $request->old('version') : $vehiculo->version;
        $vehiculo->color = $request->old('color') ? $request->old('color') : $vehiculo->color;
        $vehiculo->year = $request->old('year') ? $request->old('year') : $vehiculo->year;
        $vehiculo->kilometraje = $request->old('kilometraje') ? $request->old('kilometraje') : $vehiculo->kilometraje;
        $vehiculo->transmision = $request->old('transmision') ? $request->old('transmision') : $vehiculo->transmision;
        $vehiculo->combustible = $request->old('combustible') ? $request->old('combustible') : $vehiculo->combustible;
        $vehiculo->cliente_id = $request->old('cliente_id') ? $request->old('cliente_id') : $vehiculo->cliente_id;
        if( $request->old('cliente_id') ) {
            $rut = trim(explode(",", $request->old('cliente_id'))[0]);
            $cliente = Cliente::where("rut", $rut)->get();
            
            if( count($cliente) > 0 ){
                $vehiculo->cliente_id = $cliente[0]->id;
            }
        }

        $defaultClient = Cliente::find($vehiculo->cliente_id);

        if( $defaultClient ){
            $defaultClient = $defaultClient->rut.', '.$defaultClient->nombre;
        } else {
            $defaultClient = null;
        }

        $clients = Cliente::all();
        $ordenes = OrdenTrabajo::where("vehiculo_patente", $vehiculo->patente)->orderBy("inicio", "desc")->limit(10)->get();

        if(count($ordenes)){
            foreach($ordenes as $k => $row){
                $ordenes[$k]->inicio = $row->inicio ? date("d/m/Y", strtotime($row->inicio)) : $row->inicio;
                $ordenes[$k]->termino = $row->termino ? date("d/m/Y", strtotime($row->termino)) : $row->termino;
            }
        }

        $data = [
            'vehiculo' => $vehiculo,
            'clients' => $clients,
            'defaultClient' => $defaultClient,
            'ordenes' => $ordenes,
        ];

        return view('vehiculos/edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $vehiculo = Vehiculo::find($id);

        // Pasamos datos de request a variable requestData, la que será validada por Validator Class
        $requestData = $request->all();
        $rut = trim(explode(",", $request->cliente_id)[0]);
        $getCliente = Cliente::where("rut", $rut)->get();

        // Si se encuentra cliente por el rut obtenido entonces seteamos cliente_id
        if( count($getCliente) > 0 ){
            $requestData['cliente_id'] = $getCliente[0]->id;
            $request->cliente_id = $getCliente[0]->id;
        } else { // De lo contrario es nulo
            $requestData['cliente_id'] = null;
        }

        $rules = [
            'patente' => ['required', 'max:6'],
            'marca' => 'required|max:255',
            'modelo' => 'required|max:255',
            'version' => 'required|max:255',
            'color' => 'required|max:20',
            'year' => 'required|numeric',
            'kilometraje' => 'required|numeric',
            'transmision' => 'required',
            'combustible' => 'required',
            'cliente_id' => 'required',
        ];

        if( $vehiculo->patente != $request->patente ){
            array_push($rules['patente'], 'unique:vehiculos'); 
        }

        $validator = Validator::make($requestData,
        $rules,
        $messages = [
            'required' => 'El campo :attribute es requerido.',
            'max' => 'El campo :attribute no debe tener más de :max caracteres.',
            'patente.unique' => 'El campo :attribute ya se encuentra registrado',
            'version.required' => 'El campo :attribute es requerido.',
            'version.max' => 'El campo :attribute no debe tener más de :max caracteres.',
            'year.required' => 'El campo año es requerido.',
            'year.numeric' => 'El campo año debe ser numérico.',
            'transmision.required' => 'El campo transmisión es requerido.',
            'cliente_id.required' => 'El campo cliente es requerido. Seleccione un valor de la lista',
        ]);

        if ($validator->fails()) {
            return redirect('/vehiculos/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }

        $validated = $validator->validated();

        $vehiculo->patente = $request->patente;
        $vehiculo->marca = $request->marca;
        $vehiculo->modelo = $request->modelo;
        $vehiculo->version = $request->version;
        $vehiculo->color = $request->color;
        $vehiculo->year = $request->year;
        $vehiculo->kilometraje = $request->kilometraje;
        $vehiculo->transmision = $request->transmision;
        $vehiculo->combustible = $request->combustible;
        $vehiculo->cliente_id = $request->cliente_id;
        $vehiculo->save();

        return redirect('/vehiculos/'.$id.'/edit')->with('success', "Registro actualizado exitosamente.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        // Eliminación múltiple
        if((int)$id === 0 && $request->deleteIds && count(explode(",", $request->deleteIds)) > 0) {
            
            $deleteIds = explode(",", $request->deleteIds);
            Vehiculo::destroy($deleteIds);

            return redirect('/vehiculos')->with('success', "Registros eliminados exitosamente.");
        }
        else {
            $vehiculo = Vehiculo::find($id);
            Vehiculo::destroy($id);

            return redirect('/vehiculos')->with('success', "Registro [".$vehiculo->patente."] eliminado exitosamente.");
        }
    }
}
