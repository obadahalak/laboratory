<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IndexPatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $value=(($this->doctor_name) ? $this->doctor_name : (($this->lab_name) ? $this->lab_name : $this->company_name));


        return [
            'id'=>$this->patient_id,
            'name'=>$this->patient_name,
            'email'=>$this->patient_email,
            'phone'=>$this->patient_phone_number,
            'email'=>$this->patient_email,
            'date_of_visit'=>$this->patient_date_of_visit,
            'gender'=>$this->patient_gender,
            'age'=>$this->patient_age,
            // 'patient_anlays_id'=>$this->analys_id,
            // 'analys_name'=>$this->section_name ?? $this->anlys_name,
            'from'=>$value,
        ];
    }
}
