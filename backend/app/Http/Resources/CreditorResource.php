<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CreditorResource extends JsonResource
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
            'totalAmt' => $this->total,
            'totalPaid' => $this->paid,
            'discount' => $this->discount,
            'date' => $this->date,
            'balance' => $this->total-$this->paid,
            'user' => $this->user?$this->user->name:'',
            'supplier' => $this->supplier,
            'branch' => $this->branch,
            'business' => $this->business,
            'addedon' => Carbon::parse($this->created_at)->format("Y-m-d H:i:s a"),
        ];
    }
}
