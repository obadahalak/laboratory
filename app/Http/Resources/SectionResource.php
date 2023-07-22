<?php

namespace App\Http\Resources;

use App\Http\Resources\GetDoctorResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $key = (($this->doctor) ?  'doctor' : (($this->lab) ? 'lab' : 'company'));
        $key_Id = (($this->doctor) ?  'doctor_id' : (($this->lab) ? 'lab_id' : 'company_id'));
        if ($this->section) {
            return [
                'id' => $this->id,
                $key => (($this->doctor) ?  $this->doctor->name : (($this->lab) ? $this->lab->lab_name : $this->company->name)),
                $key_Id => (($this->doctor) ?  $this->doctor->id : (($this->lab) ? $this->lab->id : $this->company->id)),


                'section_name' => $this->section->name,
                'section_id' => $this->section->id,
                'analys_name' => $this->analyz->test_print_name ?? null,
                'analys_id' => $this->analyz->id ?? null,
                'price_doctor' => $this->price_doctor ?? null,
                'ratio_price' => $this->ratio_price ?? null,
                'price_lab' => $this->price_lab ?? null,
                'price_company' => $this->price_company ?? null,
                'send_method' => $this->sendMethod->name,
                'send_method_id' => $this->sendMethod->id,
                'emergency' => $this->emergency,
                'notes' => $this->notes,
                'price_analysis' => $this->price_analysis,
                'paid_up' => $this->paid_up,
                'duo' => $this->duo,
                'discount' => $this->discount ?? null,
                'payment_method' => $this->paymentMethod->name,
                'payment_method_id' => $this->paymentMethod->id,
                'section_test_print_name' => $this->section->test_print_name,

                'tupe_image'=>$this->section->adminTupe ? $this->section->adminTupe->image : $this->analyz->adminTupe->image,

            ];
        } else {

            return [
                'id' => $this->id,
                'name' => $this->test_print_name,
            ];
        }
    }
}
