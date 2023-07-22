<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class getSendingPateintResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $key = (($this->doctor_name) ? 'doctor' : (($this->lab_name) ? 'lab_name' : 'compnay'));

        return [
            'id' => $this->send_patients_id,
            'petient_analysis_id'=>$this->patient_analysis_id,
            'isComplete'=>$this->isComplete,
            'recived_status' => $this->recived_id==auth('lab')->user()->id ? 1 : 0,
            'account_id' => $this->account_id,
            'recived_id' => $this->recived_id,
            'patient_id' => $this->patient_id,
            'patient_email' => $this->patient_email,
            'patient_name' => $this->patient_name,
            'patitne_age' => $this->patient_age,
            'patient_date_of_visit' => $this->patient_date_of_visit,
            'patitne_phone_number' => $this->patitne_phone_number,
            'patitne_gender' => $this->gender_patient_name,
            $key => (($this->doctor_name) ? $this->doctor_name : (($this->lab_name) ? $this->lab_name : $this->company_name)),
        ];

    }
}
