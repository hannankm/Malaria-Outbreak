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
            'no_of_new_cases' => $this->no_of_new_cases,
            'date' => $this->date,
            'household_id' => $this->household_id,
            'supervisor_id' => $this->supervisor_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'malaria_cases' => $this->grouped_malaria_cases 
    ? [
        'grouped_by_status' => $this->grouped_malaria_cases->map(function ($cases, $status) {
            return [
                'status' => $status,
                'cases' => MalariaCaseResource::collection($cases),
            ];
        }),
    ] 
    : null,

        ];
        }
}
