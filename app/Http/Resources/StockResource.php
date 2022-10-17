<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $price = $this->price == 0 ? 1 : $this->price;
        $mock_price = $this->mock_price == 0 ? 1 : $this->mock_price;

        $discount = (($mock_price - $price) / $mock_price);

        return [
            'id' => $this->id,
            'price' => floatval(number_format((float)$this->price, 2, '.', '')),
            'mock_price' => floatval(number_format((float)$this->mock_price, 2, '.', '')),
            'credits' => floatval(number_format((float)$this->credits, 2, '.', '')),
            //'mock_discount' => floatval(number_format($discount, 2, '.', '')),
            'discount' => floatval(number_format($discount, 2, '.', '')),
            'cover_image' => new ImageResource($this->whenLoaded('cover_image')),
            'cover_image' => new ImageResource($this->whenLoaded('cover_image')),
            'animation_cover' => new Animation360Resource($this->whenLoaded('animation_cover')),
            //'product'=>$this->product,
            'price' => $this->price,
            'images' => $this->when(!!$this->images, function () {
                $images = $this->cover_image?->id ? $this->images->except([$this->cover_image->id]) : $this->images;
                return  ImageResource::collection($images);
            }, []),
            'videos' => VideoResource::collection($this->whenLoaded('videos')),
            'description' => $this->description,
            'name' => $this->name,
            //  'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'color' => new ColorResource($this->whenLoaded('color')),
            'colors' => $this->when(!!$this->colors, function () {
                return ColorResource::collection($this->colors);
            }),
            'ribbon' => new RibbonResource($this->whenLoaded('ribbon')),
            'categories' => $this->when(!!$this->categories, function () {
                return CategoryResource::collection($this->categories);
            }, []),
            'is_available' => (bool) $this->is_available,
            //'size' => new SizeResource($this->whenLoaded('size')),
            'sizes' => $this->when(!!$this->sizes, function () {
                return SizeResource::collection($this->sizes);
            }, []),
            'is_favorite' => $this->when($this->favorited_by_users->isNotEmpty(), function () {
                return (bool) !!$this->favorited_by_users->firstWhere('id', auth()->id());
            }, false),
            'rx_cost' => floatval(number_format((float)$this->rx_cost, 2, '.', '')),
            'nacional_cost' => floatval(number_format((float)$this->nacional_cost, 2, '.', '')),
            'urbano_cost' => floatval(number_format((float)$this->urbano_cost, 2, '.', ''))
        ];
    }
}
