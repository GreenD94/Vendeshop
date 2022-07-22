<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
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
            'id' => (int) $this->id,
            'user_id' => (int) $this->user_id,
            'value' => (float) $this->value,
            'expiration_time' => $this->expiration_time,
            'is_used' => (bool) $this->is_used,
            'is_active' => (bool)  $this->is_active,
        ];
    }
}
