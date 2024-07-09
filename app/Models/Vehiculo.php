<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehiculo extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'patente',
        'marca',
        'modelo',
        'version',
        'color',
        'year',
        'kilometraje',
        'transmision',
        'combustible',
        'cliente_id',
    ];
}
