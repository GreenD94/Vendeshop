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
        return  [
            'id' => $this->id,
            'is_active' => (bool)  $this->is_active,
            'price' => floatval(number_format((float)$this->price, 2, '.', '')),
            'price_percentage' => $this->price_percentage

        ];
    }
}
