<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ReconcilationLogResource extends JsonResource
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
            'product' => $this->product->product_name,
            'unit' => $this->unit->name,
            'actual_quantity' => $this->actual_quantity,
            'base_quantity' => $this->base_quantity,
            'branch' => $this->branch->name,
            'date' => Carbon::parse($this->created_at)->format("Y-m-d H:i:s a"),
        ];
    }
}
