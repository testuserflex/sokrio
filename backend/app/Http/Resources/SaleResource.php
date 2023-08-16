<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $orderedItems = collect($this->items)->sortByDesc('id');

        return [
            'id' => $this->id,
            'totalAmt' => round($this->total+$this->discount, 2),
            'totalPaid' => round($this->paid, 2),
            'VAT' => round($this->total*0.18, 2),
            'discount' => $this->discount,
            'balance' => round($this->total-($this->paid), 2),
            'receipt' => $this->receipt,
            'receipt' => $this->transaction_status==2 && $this->offline_receipt?$this->offline_receipt:$this->receipt,
            'received' => $this->received,
            'date' => $this->sale_date,
            'customer' => $this->customer?$this->customer->name:'',
            'customerobj' => $this->customer?new CustomerResource($this->customer):'',
            'customer_id' => $this->customer?$this->customer->id:null,
            'customerPhone' => $this->customer?$this->customer->contact:null,
            'days' => ceil((time()-strtotime($this->sale_date))/86400),
            'items' => $this->items ? SaleDetailResource::collection($orderedItems->values()) : '',
            'items_data' => $this->items,
            'user' => $this->user?$this->user->name:'',
            'branch' => $this->branch?$this->branch:'',
            'business' => $this->business,
            'business2' => new BusinessResource($this->business),
            'numpaid' => count($this->payments),
            'picking_date' => $this->picking_date??'',
            'addedon' => Carbon::parse($this->created_at)->format("Y-m-d H:i:s a"),
        ];
    }
}
