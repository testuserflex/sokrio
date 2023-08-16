<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class CustomerAccountResource extends JsonResource
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
            'amountIn'=>$this->amount_in,
            'amountOut'=>$this->amount_out,
            'Description'=>$this->description,
            'Category'=>$this->category == 1?'Sale':'Deposit',
            'customer'=>$this->customer,
            'user'=>$this->user?$this->user->name:'---',
            'branch'=>$this->branch,
            'branch'=>$this->branch,
            'business2' => new BusinessResource($this->business),
            'mode' => $this->paymode->type_name." - ".$this->paymode->name."(".$this->paymode->account_number.")",
            'added_on' =>Carbon::parse($this->created_at)->format("Y-m-d H:i:s a"),
        ];
    }
}
