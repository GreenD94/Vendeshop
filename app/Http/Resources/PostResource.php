<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'body' => $this->body,
            'created_at' => $this->created_at->toDateTimeString(),
            'is_main' => (bool)$this->is_main,
            'user_id' => (int) $this->user_id,
            'user_name' => $this->user->first_name . ' ' . $this->user->last_name,
            'stock_id' => (int)$this->stock_id,
            'main_post_id' => $this->when(!$this->is_main, function () {
                return $this->mainPost?->first()?->id;
            }, null),
            'replies' => $this->when($this->replies->isNotEmpty(), function () {
                return PostResource::collection($this->replies);
            }, null),
            'cover_image' => $this->when($this->stock?->cover_image, function () {
                return new ImageResource($this->stock?->cover_image);
            }, null),
        ];
    }
}
