<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Animation360Resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return
            [
                'id' => $this->id,
                'name' => $this->name,
                'image' => $this->when($this->image, function () {
                    return new ImageResource($this->image);
                }, []),
                'animation_360_id' => $this->animation_360_id,

            ];
    }


    public static function ChatRooms($collection)
    {
        return $collection->values()->map(function ($animation360, $key) {
            return $animation360->map(function ($frame, $key) {
                return new Animation360Resource($frame);
            });
        });
    }
}
