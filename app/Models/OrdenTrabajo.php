<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdenTrabajo extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'cliente_rut', 
        'cliente_nombre', 
        'vehiculo_patente',
        'vehiculo_marca',
        'vehiculo_modelo',
        'vehiculo_version',
        'vehiculo_color',
        'vehiculo_year',
        'vehiculo_kilometraje',
        'vehiculo_transmision',
        'vehiculo_combustible',
        'servicios',
        'detalle_cliente',
        'detalle_taller',
        'valor',
        'inicio',
        'termino',
        'estado',
    ];
}
