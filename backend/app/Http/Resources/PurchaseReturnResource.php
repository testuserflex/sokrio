<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseReturnResource extends JsonResource
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
            'product'=> $this->product,
            'quantity'=>$this->quantity,
            'reason'=>$this->reason,
            // 'mode' => $this->mode?$this->mode->type_name." - ".$this->mode->name."(".$this->mode->account_number.")":null,
            'mode' => $this->mode?:null,
            'batch'=>$this->batch,
            'unit_price' => $this->unit_buying_price,
            'unit_price_no' => number_format($this->unit_buying_price),
            'amount'=>$this->amount_refunded,
            'amount_no'=>number_format($this->amount_refunded),
            'user'=>$this->user,
            'type'=>$this->type==0?"Shop":"Store",
            'branch' => $this->branch,
            'date_recorded'=>Carbon::parse($this->created_at)->format('Y-m-d H:i:s a'),
        ];
    }
}
