<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductUnitResource extends JsonResource
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
            'product' => $this->product->product_name,
            'unit' => $this->unit,
            'unitsym' => $this->unit->symbol,
            'user' => $this->whenLoaded('user'),
            'is_base' => $this->is_base,
            'base_qty' => $this->base_quantity,
            'product_code' => $this->product_code,
            'selling' => $this->selling_price,
            'reserve' => $this->reserve_price,
            'wholesale_unitprice' => $this->wholesale_unitprice,
            'wholesale_reserveprice' => $this->wholesale_reserveprice,
            'branch' => $this->whenLoaded('branch'),
        ];
    }
}
