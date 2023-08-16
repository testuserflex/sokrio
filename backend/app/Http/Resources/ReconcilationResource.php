<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ReconcilationResource extends JsonResource
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
            'products' => $this->num,
            'type' => $this->type == 0?'Shop':'Store',
            'user' => $this->user->name,
            'branchx' => $this->whenLoaded('branch'),
            'contacts' => $this->branch->phone2?$this->branch->phone1."/".$this->branch->phone2:$this->branch->phone1,
            'branch' => $this->branch?$this->branch->name:'',
            'items' => StockReconciliationResource::collection($this->whenLoaded('items')),
            'date' => Carbon::parse($this->created_at)->format("Y-m-d H:i:s a"),
        ];
    }
}
