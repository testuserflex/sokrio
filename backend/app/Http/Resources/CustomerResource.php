<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'name' => $this->name,
            'name1' => $this->name." - " . $this->contact,
            'phone' => $this->contact,
            'user' => $this->user,
            'balance' => app('App\Http\Controllers\FunctionController')->customerBalance($this->id),
            'orders' => SaleResource::collection($this->whenLoaded('orders')),
            'addedon' => Carbon::parse($this->created_at)->format("Y-m-d H:i:s a"),
        ];
    }
}
