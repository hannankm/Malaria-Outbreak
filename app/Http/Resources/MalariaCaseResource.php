<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MalariaCaseResource extends JsonResource
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
            'status' => $this->status,
            'age_group' => $this->age_group,
            'sex' => $this->sex,
            'diagnosed' => $this->diagnosed,
            'household_stat_id' => $this->household_stat_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    
    }
}
