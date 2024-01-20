<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use User;

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
            'uuid' => $this->uuid,
            'user' => UserResource::make($this->user),
            'order' => FoodResource::make($this->food),
            'price_order' => $this->price_order,
            'total_price' => $this->price_order + $this->food->price,
            'order_type' => $this->order_type,
            'location' => LocationResource::make($this->location),
        ];
    }
}
