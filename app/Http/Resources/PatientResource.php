<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($request->patientId) {
            return [
                'id' => $this->id ?? null,
                'name' => $this->name,
                'address' => $this->address,
                'date_of_visit' => $this->date_of_visit,
                'receive_of_date' => $this->receive_of_date,
                'phone_number' => $this->phone_number,
                'age' => $this->age,
                'email' => $this->email,
                'gender_id' => $this->gender->id,
                'gender' => $this->gender->name,

                "price_analysis" => $this->price_analysis ?? 0,
                "duo" => $this->duo ?? 0,
                "paid_up" => $this->paid_up ?? 0,
                "discount" => $this->discount ?? 0,

                //     'payment_method' => $this->patieonAnalysWithData->paymentMethod,
                // 'from' => GetDoctorResource::collection($this->patieonAnalysWithData),
                'section_name' => SectionResource::collection($this->patieonAnalysWithData),
                // 'analys_name'=> SectionResource::collection($this->patieonAnalysWithData),
            ];
        } else {

            return [
                'id' => $this->id,
                'name' => $this->name,
                'address' => $this->address,
                'date_of_visit' => $this->date_of_visit,
                'receive_of_date' => $this->receive_of_date,
                'phone_number' => $this->phone_number,
                'age' => $this->age,
                'email' => $this->email,
                'gender' => $this->gender->name,
                'from' => GetDoctorResource::collection($this->patieonAnalysWithData),

            ];
        }
    }


    // switch



}
