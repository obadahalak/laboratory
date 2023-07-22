<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReceivedPatientsResource extends JsonResource
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
            'id'=>$this->id,
            'patitne_name' => $this->patieonAnalys->patient->name,
            'patitne_age' => $this->patieonAnalys->patient->age,
            'patitne_address' => $this->patieonAnalys->patient->address,
            'patitne_date_of_visit' =>$this->patieonAnalys->patient->date_of_visit,
            'patitne_phone_number' => $this->patieonAnalys->patient->phone_number,
            'patitne_gender' => $this->patieonAnalys->patient->gender->name,
            'from' => (($this->patieonAnalys->doctor) ? $this->patieonAnalys->doctor->name : (($this->patieonAnalys->company) ? $this->patieonAnalys->company->name : $this->patieonAnalys->lab->lab_name)),

        ];
    }
}
