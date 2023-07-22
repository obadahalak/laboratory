<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OutSideResource extends JsonResource
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
            'account_id' =>    $this->account_id ,
            'store_id' =>  $this->store_id ,
            'quantity' =>  $this->quantity ,
            'out_date' =>  $this->out_date ,
            'product_name' => $this->store->product_name,
            'image' => $this->store->image,
            'description' => $this->store->description,
            'company' => $this->store->company,
            'model' => $this->store->model,
            'test_unit_id' => $this->store->test_unit->id,
            'unit' => $this->store->test_unit->unit,
        ];
    }
}
