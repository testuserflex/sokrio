<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class CustomerDepositResource extends JsonResource
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
            'amount'=>$this->amount,
            'customer'=>$this->customer,
            'receipt'=>$this->receipt,
            'balance'=>new CustomerResource($this->customer),
            'user'=>$this->user,
            'mode' => $this->paymode->type_name." - ".$this->paymode->name."(".$this->paymode->account_number.")",
            'added_on' =>Carbon::parse($this->created_at)->format("Y-m-d H:i:s a"),
            'user' => $this->user?$this->user->name:'',
            'branch' => $this->branch?$this->branch:'',
            'business' => $this->business,
            'business2' => new BusinessResource($this->business),
        ];
    }
}
