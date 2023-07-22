<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
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
            'id'=>$this->doctor_id ,
            'name' => $this->doctor_name,
            'email' => $this->doctor_email,
            'password'=>$this->doctor_password,
            'gender' => $this->doctor_gender,
            'phone' => $this->doctor_phone,
            'address' => $this->doctor_address,
            'ratio' => $this->doctor_ratio,
            'created_at' => $this->doctor_created_at,

        ];
    }
}
