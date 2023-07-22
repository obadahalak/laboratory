<?php

namespace App\Http\Resources;

use App\Http\Requests\OutStoreRequest;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
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
            'id'                => $this->id,
            'product_name'      => $this->product_name,
            'expire_date'       => $this->expire_date,
            'company'           => $this->company,
            'inside_date'       => $this->created_at,
            'out_date'          => OutStoreResource::collection($this->out_date),
            'inside_quantity'   => $this->quantity,
            'outside_quantity'  => OutStoreResource::collection($this->quantity),
        ];
    }
}
