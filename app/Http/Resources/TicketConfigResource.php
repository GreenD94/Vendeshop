<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketConfigResource extends JsonResource
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
            'return_percentage' => floatval(number_format((float)$this->return_percentage, 4, '.', '')),
            'return_price' => floatval(number_format((float)$this->return_price, 2, '.', '')),
            'minimum_spend' => floatval(number_format((float)$this->minimum_spend, 2, '.', '')),

        ];
    }
}
