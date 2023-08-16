<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource
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
            'product'=> new ProductResource($this->product),
            'buying_price'=>$this->buying_price,
            'quantity'=>$this->quantity,
            'branch'=>$this->branch,


        ];
    }
}
