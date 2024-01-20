<?php

namespace App\Http\Resources;

use App\Models\Restaurant;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request):array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'type' => $this->type->name,
            'phone' => PhoneResource::make($this->phone),
            'location' => LocationResource::make($this->location),
            'menu' => FoodResource::collection($this->food),
        ];
    }
}
