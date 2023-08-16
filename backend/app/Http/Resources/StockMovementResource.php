<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class StockMovementResource extends JsonResource
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
            'date'=>$this->date,
            'product'=> $this->product->product_name,
            'from_quantity'=>$this->from_quantity,
            'qty_in'=>$this->quantity_in,
            'qty_out'=>$this->quantity_out,
            'user'=>$this->user->name,
            'type'=>$this->type,
            'date_recorded'=>Carbon::parse($this->created_at)->format('Y-m-d H:i:s a'),
        ];
    }
}
