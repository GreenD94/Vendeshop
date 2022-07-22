<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class OrderResource extends JsonResource
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
            'address' => [
                'id' => $this->address_id,
                'address' => $this->address_address,
                'city_id' => (int) $this->address_city_id,
                'street' => $this->address_street,
                'city_name' => $this->address_city_name,
                'postal_code' => $this->address_postal_code,
                'deparment' => $this->address_deparment,
                'phone_number' => $this->address_phone_number,
                'state_id' => $this->address_state_id,
                'state_name' => $this->address_state_name,
            ],
            'billing_address' => [
                'id' => $this->billing_address_id,
                'address' => $this->billing_address_address,
                'city_id' => (int) $this->billing_address_city_id,
                'street' => $this->billing_address_street,
                'city_name' => $this->billing_address_city_name,
                'postal_code' => $this->billing_address_postal_code,
                'deparment' => $this->billing_address_deparment,
                'phone_number' => $this->billing_address_phone_number,
                'state_id' => $this->billing_address_state_id,
                'state_name' => $this->billing_address_state_name,
            ],
            'user' => [
                'id' => $this->user_id,
                'first_name' => $this->user_first_name,
                'last_name' => $this->user_last_name,
                'email' => $this->user_email,
                'phone' => $this->user_phone,
                'birth_date' => $this->user_birth_date,
                'avatar' => [
                    'id' => $this->user_avatar_id,
                    'url' => Storage::disk('s3')->url(
                        $this->user_avatar_url
                    ),
                ],
            ],

            'status_log' => $this->when(!!$this->status_Logs, function () {
                return OrderStatusLogsResource::collection($this->status_Logs);
            }, []),

            'status' => $this->when(!!$this->status, function () {
                return new OrderStatusLogsResource($this->status);
            }, null),

            'details' => $this->when(!!$this->details, function () {
                return OrderDetailResource::collection($this->details);
            }, []),

            'tickets' => $this->when(!!$this->tickets, function () {
                return OrderTicketsResource::collection($this->tickets);
            }, []),
            'payment_type' => [
                'id' => $this->payment_type_id,
                'name' => $this->payment_type_name,
            ],
            'total' => $this->total,
            'payed' => 0,
            'created_at' => $this->created_at,
            'shipping_cost' => floatval(number_format((float)$this->shipping_cost, 2, '.', '')),

        ];
    }
}
