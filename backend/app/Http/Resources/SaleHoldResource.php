<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleHoldResource extends JsonResource
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
            'identification' => $this->identification,
            'user' => $this->user?$this->user->name:'',
            'branch' => $this->branchx,
            'business' => $this->business,
            'inCart' => $this->items->count(),
            'items' => SaleCartResource::collection($this->items),
            'filterdate' => Carbon::parse($this->created_at)->format('Y-m-d'),
            'dateAdded' => Carbon::parse($this->created_at)->format('d/m/Y | h:i A'),
        ];
    }
}
