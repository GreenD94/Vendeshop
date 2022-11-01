<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCost extends Model
{
    use HasFactory;
    static $RX = 1;
    static $NACIONAL = 2;
    static $URBANO = 3;
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
    protected $appends = ['type'];


    public function getTypeAttribute()
    {
        if ($this->isUrbano()) return ShippingCost::$URBANO;
        if ($this->isRx()) return ShippingCost::$RX;
        if ($this->isNacional()) return ShippingCost::$NACIONAL;
    }


    public function scopeFindFromGoogleMaps($query, $googleMpasData)
    {

        $query->whereNotNull('tipo_envio');
        $googleMpasData->each(function ($item, $key) use ($query) {
            $query->orWhere('poblacion_destino', $item);
        });
        $googleMpasData->each(function ($item, $key) use ($query) {
            $query->orWhere('departamento_destino', $item);
        });
        return $query;
    }

    public function isUrbano()
    {

        return $this->tipo_envio == "URBANO";
    }

    public function isNacional()
    {

        return $this->tipo_envio == "NACIONAL";
    }


    public function isRx()
    {

        return $this->tipo_envio == "RX";
    }
}
