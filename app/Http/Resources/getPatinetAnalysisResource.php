<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class getPatinetAnalysisResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id'=>$this->patient_analalys_id,
            'section_name'  => $this->section_name ? $this->section_name:  $this->test_print_name,
            'analys_name' => $this->analys_name,
            'tupe'=>$this->section_tupe ? $this->section_tupe : $this->analys_tupe,
        ];
    }
}
