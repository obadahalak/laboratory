<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AntibioticResource;

class AnalysResource extends JsonResource
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
 'id' => $this->id,
            'test_code' => $this->test_code,
            'test_print_name' => $this->test_print_name,
            'price_for_patient' => $this->price_for_patient,
            'price_for_lap' => $this->price_for_lap,
            'price_for_company' => $this->price_for_company,
            'class_report' => $this->class_report,

            'test_method' => $this->testMethod->test_method,
            'test_method_id' => $this->testMethod->id,

            'test_unit_id' => $this->testUnit->id,
            'test_unit' => $this->testUnit->unit,

            'tupe_id' => $this->adminTupe->id,
            'tupes' => $this->adminTupe->tupe,

            'anti' => AntibioticResource::collection($this->antibioticSections ?? $this->antibiotics),
        ];
    }
}
