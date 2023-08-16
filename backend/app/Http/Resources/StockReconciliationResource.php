<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StockReconciliationResource extends JsonResource
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
            'from' => $this->from,
            'to' => $this->to,
            'diff' => abs($this->to-$this->from),
            'amount' => number_format(abs(($this->to-$this->from)*$this->buying_price)),
            'baseunit' => $this->product->unit->symbol,
            'log' => ReconcilationLogResource::collection($this->log),
            'product' => $this->product->product_name,
        ];
    }
}
