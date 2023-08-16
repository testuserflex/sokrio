<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class PriceChangeResource extends JsonResource
{

    public function getType($type){
        if($type == 1){
            return 'Store';
        }else{
            return 'Shop';
        }
    }
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
            'product' => $this->product->product_name,
            'product_id' => $this->product->id,
            'old_price' => number_format($this->old_price),
            'new_price' => number_format($this->new_price),
            'recordedby' => $this->user->name,
            'type' => $this->getType($this->type),
            'branch' => $this->branch->name,
            'business' => $this->business->name,
            'date' => Carbon::parse($this->created_at)->format("Y-m-d"),
            'recordedon' => Carbon::parse($this->created_at)->format("Y-m-d H:i:s a"),
        ];
    }
}
