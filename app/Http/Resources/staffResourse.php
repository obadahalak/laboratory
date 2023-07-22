<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class staffResourse extends JsonResource
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
            'account_id' =>$this->account_id,
            'name'=>$this->name,
            'image'=>$this->id,
            'email' =>$this->account_id,
            'phone'=>$this->name,
            'DOB'=>$this->id,
            'address' =>$this->account_id,
            'work_start'=>$this->name,
        ];
    }
}
