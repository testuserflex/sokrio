<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
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
            'supplier' => $this->supplier?$this->supplier->name:'Not recorded',
            'supplierobj' => $this->supplier??'----',
            'totAmt' => round(($this->total+$this->discount), 2),
            'amtPaid' => round($this->paid, 2),
            'Balance' => round($this->total - $this->paid, 2),
            'date' => $this->date,
            'days' => ceil((time()-strtotime($this->date))/86400),
            'discount' => $this->discount,
            'user' => $this->user->name,
            'numpaid' => count($this->payments),
            'payments' => $this->payments,
            'branch' => $this->branch?$this->branch->name:'',
            'branchobj' => $this->branch,
            'business' => $this->business,
            'business2' => new BusinessResource($this->business),
            'items' => PurchaseDetailResource::collection($this->items),
            'dateAdded' => Carbon::parse($this->created_at)->format('d/m/Y | h:i A'),
        ];
    }
}
