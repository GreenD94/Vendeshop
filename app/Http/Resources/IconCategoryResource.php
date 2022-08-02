<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IconCategoryResource extends JsonResource
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
            'name' => $this->name,
            'is_favorite' => (bool) $this->is_favorite,
            'color' => $this->color,

            'image' => $this->when($this->image, function () {
                return new ImageResource($this->image);
            }, []),
        ];
    }
}
