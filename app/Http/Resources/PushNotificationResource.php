<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Boolean;

class PushNotificationResource extends JsonResource
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
                'user_id' => (int) $this->user_id,
                'tittle' => $this->tittle,
                'body' => json_decode($this->body),
                'is_new' => (bool) $this->is_new,
                'is_check' => (bool)$this->is_check,
                'event' => $this->when($this->event, function () {
                    return new PushNotificationEventResource($this->event);
                }, null),
            ];
    }
}
