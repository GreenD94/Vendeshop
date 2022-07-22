<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
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
            'id' => $this->stock_id,
            'quantity' => $this->quantity,
            'price' => floatval(number_format((float)$this->price, 2, '.', '')),
            'mock_price' => floatval(number_format((float)$this->mock_price, 2, '.', '')),
            'credits' => floatval(number_format((float)$this->credits, 2, '.', '')),
            'discount' => floatval(number_format((float)$this->discount, 2, '.', '')),
            'cover_image' => [
                "id" => $this->cover_image_id,
                'url' => Storage::disk('s3')->url(
                    $this->cover_image_url
                ),
            ],

            'price' => $this->price,
            'description' => $this->description,
            'name' => $this->name,
            'color' => [
                "color_id" => $this->cover_image_id,
                'hex' => $this->color_hex
            ],



            'size' => [
                "color_id" => $this->size_id,
                'size' => $this->size_size
            ],



        ];
    }
}
