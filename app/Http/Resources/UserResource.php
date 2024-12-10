<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'status' => $this->status,
            'region' => $this->when($this->region_id, function () {
                return $this->region ? $this->region->name : null; 
            }),
            'woreda' => $this->when($this->woreda_id, function () {
                return $this->woreda ? $this->woreda->name : null; 
            }),
            'roles' => $this->roles->pluck('name'), 
            'created_at' => $this->created_at->toDateString(),
            'updated_at' => $this->updated_at->toDateString(),
        ];
    }
}
