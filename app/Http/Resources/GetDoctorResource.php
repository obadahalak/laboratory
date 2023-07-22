<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GetDoctorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $key=(($this->doctor) ?  'doctor' : (($this->lab) ? 'lab' : 'company'));
        return [
           'id'=>(($this->doctor) ?  $this->doctor->id : (($this->lab) ? $this->lab->id : $this->company->id)) ,
           'name'=>(($this->doctor) ?  $this->doctor->name : (($this->lab) ? $this->lab->lab_name : $this->company->name)) ,
           'key'=>$key
        ];
    }
}
