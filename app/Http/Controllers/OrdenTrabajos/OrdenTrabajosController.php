<?php

namespace App\Http\Controllers\OrdenTrabajos;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\OrdenTrabajo;
use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Models\Servicio;
use App\Models\Event;
use App\Models\Estado;

class OrdenTrabajosController extends Controller
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
            $rows = OrdenTrabajo::where('cliente_rut', 'like', '%'.$querystring.'%')
            ->orWhere('cliente_nombre', 'like', '%'.$querystring.'%')
            ->orWhere('cliente_patente', 'like', '%'.$querystring.'%')
            ->paginate(10);
        }
        else {
            $rows = OrdenTrabajo::paginate(10);
        }

        if(count($rows)){
            foreach($rows as $k => $row){
                $rows[$k]->inicio = $row->inicio ? date("d/m/Y", strtotime($row->inicio)) : $row->inicio;
                $rows[$k]->termino = $row->termino ? date("d/m/Y", strtotime($row->termino)) : $row->termino;
            }
        }

        $data = [
            'ordenes' => $rows,
            'countItems' => $rows->count(),
            'currentPage' => $rows->currentPage(),
            'previousPageUrl' => $rows->previousPageUrl() . ( ($rows->previousPageUrl() && $querystring != "") ? "&query=" . $querystring : "" ),
            'nextPageUrl' => $rows->nextPageUrl() . ( ($rows->nextPageUrl() && $querystring != "") ? "&query=" . $querystring : "" ),
            'lastPage' => $rows->lastPage(),
            'perPage' => $rows->perPage(),
            'querystring' => $querystring,
        ];

        return view('ordentrabajos/index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $clientes = [];
        $servicios = Servicio::all();
        $estados = Estado::all();
        $vehiculos = Vehiculo::all();

        if( $request->old('vehiculo_id') ){
            $patente = trim(explode(",", $request->old('vehiculo_id'))[0]);
            $rs = Vehiculo::where("patente", $patente)->get();

            if( count($rs) > 0 ){
                $clientes = cliente::where('id', $rs[0]->cliente_id)->get();
            }
        }

        $data = [
            'clientes' => $clientes,
            'vehiculos' => $vehiculos,
            'servicios' => $servicios,
            'estados' => $estados,
        ];
        return view('ordentrabajos/form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
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

        $patente = trim(explode(",", $request->vehiculo_id)[0]);
        $getVehiculo = Vehiculo::where("patente", $patente)->get();

        // Si se encuentra cliente por el rut obtenido entonces seteamos cliente_id
        if( count($getVehiculo) > 0 ){
            $requestData['vehiculo_id'] = $getVehiculo[0]->id;
            $request->vehiculo_id = $getVehiculo[0]->id;
        } else { // De lo contrario es nulo
            $requestData['vehiculo_id'] = null;
        }

        $validator = Validator::make($requestData, [
            'cliente_id' => 'required',
            'vehiculo_id' => 'required',
            'inicio' => 'required',
            'termino' => 'required',
            'valor' => 'required',
            'estado' => 'required',
        ],
        $messages = [
            'required' => 'El campo :attribute es requerido.',
            'cliente_id.required' => 'El campo Cliente es requerido. Selecciona un valor de la lista.',
            'vehiculo_id.required' => 'El campo Vehículo es requerido. Selecciona un valor de la lista.',
            'termino.required' => 'El campo término es requerido.',
        ]);

        if ($validator->fails()) {
            return redirect('/ordentrabajos/create')
                        ->withErrors($validator)
                        ->withInput();
        }

        $validated = $validator->validated();

        $cliente = Cliente::find($request->cliente_id);
        $vehiculo = Vehiculo::find($request->vehiculo_id);

        $orden = OrdenTrabajo::create([
            'cliente_rut' => $cliente->rut, 
            'cliente_nombre' => $cliente->nombre, 
            'vehiculo_patente' => $vehiculo->patente,
            'vehiculo_marca' => $vehiculo->marca,
            'vehiculo_modelo' => $vehiculo->modelo,
            'vehiculo_version' => $vehiculo->version,
            'vehiculo_color' => $vehiculo->color,
            'vehiculo_year' => $vehiculo->year,
            'vehiculo_kilometraje' => $vehiculo->kilometraje,
            'vehiculo_transmision' => $vehiculo->transmision,
            'vehiculo_combustible' => $vehiculo->combustible,
            'servicios' => $request->servicios ? json_encode(explode("|", $request->servicios)) : null,
            'detalle_cliente' => $request->detalle_cliente,
            'detalle_taller' => $request->detalle_taller,
            'valor' => $request->valor,
            'inicio' => $request->inicio,
            'termino' => $request->termino,
            'estado' => $request->estado,
        ]);

        // Se crea el evento
        if( $orden->id ){
            // Inicio
            Event::create([
                'event_name' => 'Recepción: '.$vehiculo->marca.' '.$vehiculo->modelo.' ('.$vehiculo->patente.')',
                'event_description' => ($request->servicios) ? str_replace("|", ", ", $request->servicios) : "",
                'event_start' => $request->inicio,
                'event_relationship' => 'orden_trabajos',
                'event_id' => $orden->id,
            ]);

            // Término
            Event::create([
                'event_name' => 'Entrega: '.$vehiculo->marca.' '.$vehiculo->modelo.' ('.$vehiculo->patente.')',
                'event_description' => ($request->servicios) ? str_replace("|", ", ", $request->servicios) : "",
                'event_start' => $request->termino,
                'event_relationship' => 'orden_trabajos',
                'event_id' => $orden->id,
            ]);
        }

        return redirect('/ordentrabajos/create')->with('success', "Registro creado exitosamente.");
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
        $row = OrdenTrabajo::find($id);
        $servicios = Servicio::All();
        $estados = Estado::all();
        $vehiculos = Vehiculo::all();
        $vehiculo = Vehiculo::where("patente", $row->vehiculo_patente)->get();


        $orden = [];
        $orden['id'] = $row->id;
        $orden['cliente_id'] = $row->cliente_rut.', '.$row->cliente_nombre;
        $orden['vehiculo_id'] = $row->vehiculo_patente.', '.$row->vehiculo_marca.' '.$row->vehiculo_modelo;
        $orden['servicios'] = $request->old('servicios') ? $request->old('servicios') : $row->servicios;

        if($row->servicios){
            $orden['servicios'] = implode("|", json_decode($row->servicios));
        } else {
            $orden['servicios'] = $request->old('servicios') ? $request->old('servicios') : "";
        }

        $orden['detalle_cliente'] = $request->old('detalle_cliente') ? $request->old('detalle_cliente') : $row->detalle_cliente;
        $orden['detalle_taller'] = $request->old('detalle_taller') ? $request->old('detalle_taller') : $row->detalle_taller;
        $orden['inicio'] = $request->old('inicio') ? $request->old('inicio') : $row->inicio;
        $orden['termino'] = $request->old('termino') ? $request->old('termino') : $row->termino;
        $orden['valor'] = $request->old('valor') ? $request->old('valor') : $row->valor;
        $orden['estado'] = $request->old('estado') ? $request->old('estado') : $row->estado;

        $clientes = Cliente::where("id", $vehiculo[0]->cliente_id)->get();
        $servicios = Servicio::all();
        $estados = Estado::all();

        $data = [
            'clientes' => $clientes,
            'servicios' => $servicios,
            'vehiculos' => $vehiculos,
            'orden' => (object)$orden,
            'estados' => $estados,
        ];

        return view('ordentrabajos/edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $orden = OrdenTrabajo::find($id);

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

        $patente = trim(explode(",", $request->vehiculo_id)[0]);
        $getVehiculo = Vehiculo::where("patente", $patente)->get();

        // Si se encuentra cliente por el rut obtenido entonces seteamos cliente_id
        if( count($getVehiculo) > 0 ){
            $requestData['vehiculo_id'] = $getVehiculo[0]->id;
            $request->vehiculo_id = $getVehiculo[0]->id;
        } else { // De lo contrario es nulo
            $requestData['vehiculo_id'] = null;
        }

        $validator = Validator::make($requestData, [
            'cliente_id' => 'required',
            'vehiculo_id' => 'required',
            'inicio' => 'required',
            'termino' => 'required',
            'valor' => 'required',
            'estado' => 'required',
        ],
        $messages = [
            'required' => 'El campo :attribute es requerido.',
            'cliente_id.required' => 'El campo Cliente es requerido. Selecciona un valor de la lista.',
            'vehiculo_id.required' => 'El campo Vehículo es requerido. Selecciona un valor de la lista.',
            'termino.required' => 'El campo término es requerido.',
        ]);

        if ($validator->fails()) {
            return redirect('/ordentrabajos/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }

        $validated = $validator->validated();

        $cliente = Cliente::find($request->cliente_id);
        $vehiculo = Vehiculo::find($request->vehiculo_id);

        $orden->cliente_rut = $cliente->rut;
        $orden->cliente_nombre = $cliente->nombre;
        $orden->vehiculo_patente = $vehiculo->patente;
        $orden->vehiculo_marca = $vehiculo->marca;
        $orden->vehiculo_modelo = $vehiculo->modelo;
        $orden->vehiculo_version = $vehiculo->version;
        $orden->vehiculo_color = $vehiculo->color;
        $orden->vehiculo_year = $vehiculo->year;
        $orden->vehiculo_kilometraje = $vehiculo->kilometraje;
        $orden->vehiculo_transmision = $vehiculo->transmision;
        $orden->vehiculo_combustible = $vehiculo->combustible;
        $orden->servicios = $request->servicios ? json_encode(explode("|", $request->servicios)) : null;
        $orden->detalle_cliente = $request->detalle_cliente;
        $orden->detalle_taller = $request->detalle_taller;
        $orden->valor = $request->valor;
        $orden->inicio = $request->inicio;
        $orden->termino = $request->termino;
        $orden->estado = $request->estado;
        $orden->save();

        // Se eliminan eventos existentes
        $evento = Event::where('event_relationship', 'orden_trabajos')->where('event_id', $orden->id)->delete();

        // Inicio
        Event::create([
            'event_name' => 'Recepción: '.$vehiculo->marca.' '.$vehiculo->modelo.' ('.$vehiculo->patente.')',
            'event_description' => ($request->servicios) ? str_replace("|", ", ", $request->servicios) : "",
            'event_start' => $request->inicio,
            'event_relationship' => 'orden_trabajos',
            'event_id' => $orden->id,
        ]);

        // Término
        Event::create([
            'event_name' => 'Entrega: '.$vehiculo->marca.' '.$vehiculo->modelo.' ('.$vehiculo->patente.')',
            'event_description' => ($request->servicios) ? str_replace("|", ", ", $request->servicios) : "",
            'event_start' => $request->termino,
            'event_relationship' => 'orden_trabajos',
            'event_id' => $orden->id,
        ]);

        return redirect('/ordentrabajos/'.$id.'/edit')->with('success', "Registro actualizado exitosamente.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Eliminación múltiple
        if((int)$id === 0 && $request->deleteIds && count(explode(",", $request->deleteIds)) > 0) {
            
            $deleteIds = explode(",", $request->deleteIds);
            OrdenTrabajo::destroy($deleteIds);
            Event::where('event_relationship', 'orden_trabajos')->whereIn('event_id', $deleteIds)->delete();

            return redirect('/ordentrabajos')->with('success', "Registros eliminados exitosamente.");
        }
        else {
            OrdenTrabajo::destroy($id);
            Event::where('event_relationship', 'orden_trabajos')->where('event_id', $id)->delete();
            return redirect('/ordentrabajos')->with('success', "Registro eliminado exitosamente.");
        }
    }

    public function getVehiculosByClienteId(Request $request){
        if($request->ajax()) {
            $parseClienteId = explode(",", $request->cliente_id);
            $cliente = Cliente::where("rut", trim($parseClienteId[0]))->get();
            
            if( count($cliente) > 0 ) {
                $cliente_id = $cliente[0]->id;
                $vehiculos = Vehiculo::where('cliente_id', $cliente_id)->get();
                return response()->json($vehiculos);
            } else {
                return response()->json([]);
            }
        }
    }

    public function getClienteByVehiculoId(Request $request){
        if($request->ajax()) {
            $parseId = explode(",", $request->vehiculo_id);
            $vehiculo = Vehiculo::where("patente", trim($parseId[0]))->get();
            
            if( count($vehiculo) > 0 ) {
                $cliente_id = $vehiculo[0]->cliente_id;
                $cliente = Cliente::where('id', $cliente_id)->get();
                return response()->json($cliente);
            } else {
                return response()->json([]);
            }
        }
    }
}
