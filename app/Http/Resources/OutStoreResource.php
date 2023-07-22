<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OutStoreResource extends JsonResource
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
            'out_date' => $this->out_date,
            'out_quantity' => $this->quantity,
            'id'                => $this->store->id,
            'product_name'      => $this->store->product_name,
            'expire_date'       => $this->store->expire_date,
            'company'           => $this->store->company,
            'inside_date'       => $this->store->created_at,
            // 'out_date'          => OutStoreResource::collection($this->out_date),
            'inside_quantity'   => $this->store->quantity,
            // 'outside_quantity'  => OutStoreResource::collection($this->quantity),
        ];
    }
}
