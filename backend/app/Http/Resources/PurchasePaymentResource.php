<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchasePaymentResource extends JsonResource
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
            'receipt' => $this->receipt,
            'supplier' => $this->purchase?$this->purchase->supplier->name:'Not given',
            'supplier_id' => $this->purchase?$this->purchase->supplier->id:0,
            'amount' => $this->amount,
            'date' => $this->date,
            'user' => $this->user->name,
            'receipt' => $this->receipt,
            'mode' => $this->paymentmode?$this->paymentmode->type_name." - ".$this->paymentmode->name."(".$this->paymentmode->account_number.")":null,
            'payments' => $this->recno,
            'dateAdded' => Carbon::parse($this->created_at)->format('d/m/Y | h:i A'),
        ];
    }
}
