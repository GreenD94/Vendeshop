<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class addressResource extends JsonResource
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
            'address' => $this->address,
            'city_id' => (int) $this->city_id,
            'street' => $this->street,
            'city_name' => $this->city_name,
            'postal_code' => $this->postal_code,
            'deparment' => $this->deparment,
            'phone_number' => $this->phone_number,
            'is_favorite' => (bool)$this->is_favorite,
            'user_id' => $this->when($this->users->isNotEmpty(), function () {
                return $this->users->first()->id;
            }, null),
        ];
    }
}
