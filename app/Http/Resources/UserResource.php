<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => str_replace('vvvvv', "", $this->last_name),
            'email' => $this->email,
            'phone' => $this->phone,
            'birth_date' => $this->birth_date,
            'is_new' => (bool) $this->is_new,
            'avatar' => $this->when($this->avatar, function () {
                return new ImageResource($this->avatar);
            }, collect([])),
            'tickets' => $this->when($this->tickets, function () {
                return  TicketResource::collection($this->tickets);
            }, null),
            'address' => $this->when($this->addresses, function () {;
                return    addressResource::collection($this->addresses);
            }, null),
            'roles' => $this->when($this->roles, function () {;
                return    RoleResource::collection($this->roles);
            }, null),
            'dni' => $this->dni,
            'tickets_total' => $this->when($this->tickets, function () {
                return floatval(number_format((float) $this->tickets->sum('value'), 2, '.', ''));
            }, null),

        ];
    }
}
