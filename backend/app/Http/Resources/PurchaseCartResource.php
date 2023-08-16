<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseCartResource extends JsonResource
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
            'product' => $this->product,
            'quantity' => $this->quantity,
            'total_price' => round($this->quantity * $this->unit_price, 2),
            'unitprice' => round($this->unit_price, 2),
            'unitsellingprice' => $this->unit_sellingprice,
            'unitsym' => $this->punit?$this->punit->unit->symbol:'',
            'supplier' => $this->supplier,
            'category' => $this->category,
            'batch' => $this->batch,
            'expiry' => $this->expiry,
            'user' => $this->user,
            'branch' => $this->branch,
        ];
    }
}
