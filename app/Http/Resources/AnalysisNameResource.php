<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnalysisNameResource extends JsonResource
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
            'name'=>$this->test_print_name,
            'price_for_patient'=>$this->price_for_patient,
            'price_for_lab'=>$this->price_for_lap,
            'price_for_company'=>$this->price_for_company,
            'class_report'=>$this->class_report,
            'section_id'=>$this->section_id,

        ];
    }
}
