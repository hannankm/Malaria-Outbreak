<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HouseholdStatResource extends JsonResource
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
            'no_of_active_cases' => $this->no_of_active_cases,
            'no_of_death' => $this->no_of_death,
            'no_of_people_at_risk' => $this->no_of_people_at_risk,
            'no_of_recovered' => $this->no_of_recovered,
            'date' => $this->date,
            'household_id' => $this->household_id,
            'supervisor_id' => $this->supervisor_id,
            'household' => new HouseholdResource($this->household),  // Optional, if you want to include Household data
            'supervisor' => new UserResource($this->supervisor),  // Optional, if you want to include Supervisor data
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
        }
}
