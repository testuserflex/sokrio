<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class BatchResource extends JsonResource
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
            'product' => new ProductResource($this->product),
            'qty_in' => $this->quantity_in,
            'qty_out' => $this->quantity_out,
            'expiry' => $this->expiry_date,
            'category' => $this->type == 1?'Shop':'Store',
            'days' => ceil((strtotime($this->expiry_date)-time())/86400),
            'is_expired' => $this->is_expired,
            'type' => $this->type,
            'expired_qty' => $this->quantity_expired,
            'user' => $this->whenLoaded('user'),
            'branch' => $this->whenLoaded('branch'),
            'addedOn' => Carbon::parse($this->created_at)->format("Y-m-d H:i:s a"),
            'batch' => $this->batch_number,
        ];
    }
}
