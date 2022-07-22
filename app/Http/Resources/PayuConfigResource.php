<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PayuConfigResource extends JsonResource
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
            'is_active' => (bool) $this->is_active,
            'api_key' =>  $this->api_key,
            'api_login' =>  $this->api_login,
            'merchant_id' =>  $this->merchant_id,
            'is_test' =>  (bool)  $this->is_test,
            'payments_custom_url' =>  $this->payments_custom_url,
            'reports_custom_url' =>  $this->reports_custom_url,
            'account_id' =>  $this->account_id,
            'description' =>  $this->description,
            'tax_value' =>  $this->tax_value,
            'tax_return_base' =>  $this->tax_return_base,
            'currency' =>  $this->currency,
            'installments_number' =>  $this->installments_number,
        ];
    }
}
