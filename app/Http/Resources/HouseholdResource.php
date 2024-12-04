<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HouseholdResource extends JsonResource
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
            'full_name' => $this->full_name,
            'phone_number' => $this->phone_number,
            'house_number' => $this->house_number,
            'spouse_name' => $this->spouse_name,
            'spouse_phone_number' => $this->spouse_phone_number,
            'no_of_adults' => $this->no_of_adults,
            'no_of_children' => $this->no_of_children,
            'location' => $this->location,
            'supervisor' => new UserResource($this->supervisor),
            'woreda' => new WoredaResource($this->woreda),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
