<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShippingCostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'is_active' => (bool) $this->is_active,
            'price' => floatval(number_format((float) $this->price, 2, '.', '')),
            'price_percentage' => $this->price_percentage,
            'poblacion_origen' => $this->poblacion_origen,
            'poblacion_destino' => $this->poblacion_destino,
            'departamento_destino' => $this->departamento_destino,
            'tipo_envio' => $this->tipo_envio,
            'd2021_paq' => $this->d2021_paq,
            'd2021_msj' => $this->d2021_msj,
            'd1kg_msj' => $this->d1kg_msj,
            'd2kg_msj' => $this->d2kg_msj,
            'd3kj_msj' => $this->d3kj_msj,
            'd4kg_msj' => $this->d4kg_msj,
            'd5kg_msj' => $this->d5kg_msj,

        ];
    }
}
