<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class getClassReportsResource extends JsonResource
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
            'name' => $this->test_print_name,
            'class_report'=>$this->class_report,
            'antibiotics'=>AntibioticResource::collection($this->antibiotics),


        ];
    }
}
