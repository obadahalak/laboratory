<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class myPatientResource extends JsonResource
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
            'id' => $this->patient->id,
            'name' => $this->patient->name,
            'address' => $this->patient->address,
            'date_of_visit' => $this->patient->date_of_visit,
            'receive_of_date' => $this->patient->receive_of_date,
            'phone_number' => $this->patient->phone_number,
            'age' => $this->patient->age,
            'email' => $this->patient->email,
            'gender' => $this->patient->gender->name,
            'section'=>$this->section->name ?? $this->section->test_print_name,
            'analys'=>$this->analys->test_print_name ?? null,
        ];
    }
}
