<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCost extends Model
{
    use HasFactory;
    protected $fillable = [
        'is_active',
        'price',
        'price_percentage',
        'poblacion_origen',
        'poblacion_destino',
        'departamento_destino',
        'tipo_envio',
        'd2021_paq',
        'd2021_msj',
        'd1kg_msj',
        'd2kg_msj',
        'd3kg_msj',
        'd4kg_msj',
        'd5kg_msj'
    ];
}
