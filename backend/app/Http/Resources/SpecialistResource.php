<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SpecialistResource extends JsonResource
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
            'user_id' => $this->user_id,
            'salon_id' => $this->salon_id,
            'name' => $this->name,
            'position' => $this->getTranslatedPosition(),
            'bio' => $this->getTranslatedBio(),
            'avatar' => $this->avatar,
            'working_hours' => $this->working_hours,
            'active' => $this->active,
            'salon' => new SalonResource($this->whenLoaded('salon')),
        ];
    }
}
