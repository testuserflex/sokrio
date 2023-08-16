<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseDetailResource extends JsonResource
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
            'product_id' => $this->product?$this->product->id:'',
            'product' => $this->product?$this->product->product_name:'',
            'qty' => $this->quantity,
            'usymbol' =>  $this->punit?$this->punit->unit->symbol:'',
            'unitPrice' => round($this->buying_price, 2),
            'total' => round($this->buying_price*$this->quantity, 2),
            'batch' => $this->batch,
            'branch' => $this->branch->name,
            'expire' => $this->expiry,
            'category' => $this->category,
            'purchase' => $this->purchase,
            'Returned' => $this->returned,
            'return' => $this->return_status,
            'user' => $this->user->name,
            'dateAdded' => Carbon::parse($this->created_at)->format("Y-m-d H:i:s a"),
        ];
    }
}
