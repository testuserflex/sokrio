<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SpoiltStockResource extends JsonResource
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
            'id' =>$this->id,
            'product' => $this->product,
            'user' => $this->user,
            'date' => $this->date_removed,
            'batch' => $this->batchx?$this->batchx->batch_number:null,
            'qty' => $this->quantity." ".$this->product->unit->symbol,
            'buyingprice' => number_format($this->unit_buying_price),
            'reason' => $this->reason,
            'expired' => $this->expired,
            'confirm' => $this->confirm,
            'dateAdded' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s a'),
            'branch' => $this->branch
        ];
    }
}
