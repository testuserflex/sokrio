<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleCartResource extends JsonResource
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
            'name' => $this->product->product_name,
            'product_id' => $this->product->id,
            'instock' => $this->type==1?$this->product->quantity:0,
            'quantity' => $this->quantity,
            'quantity_taken' => $this->quantity_taken,
            'selling' => $this->selling_price,
            'description' => $this->description??'',
            'baseQty' => $this->type==1?$this->unitx->base_quantity:1,
            'unit' => $this->unitx?$this->unitx->unit->symbol:null,
            'unit_id' => $this->unitx?$this->unitx->id:null,
            'user' => $this->user,
            'branch' => $this->branch->name,
            'batch' => $this->batch,
        ];
    }
}
