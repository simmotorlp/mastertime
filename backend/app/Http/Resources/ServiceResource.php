<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'salon_id' => $this->salon_id,
            'category_id' => $this->category_id,
            'name' => $this->name,
            'description' => $this->getDescription(),
            'price' => $this->price,
            'discounted_price' => $this->discounted_price,
            'duration' => $this->duration,
            'active' => $this->active,
            'salon' => new SalonResource($this->whenLoaded('salon')),
            'category' => new ServiceCategoryResource($this->whenLoaded('category')),
        ];
    }
}
