<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
class ServiceResource extends JsonResource
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
            'name' =>$this->product_name,
            'selling' =>$this->selling_price,
            'cost' =>$this->stock->buying_price,
            'business_product' => new BusinessProductResource($this->whenLoaded('businessproduct')),
            'user' => $this->user->name,
            'branch' => $this->whenLoaded('branch'),
            'added_on' =>Carbon::parse($this->created_at)->format("Y-m-d H:i:s a")
        ];
    }
}
