<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MoneyTransferResource extends JsonResource
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
            'from' => $this->sourcemode,
            'to' => $this->destinationmode,
            'amount' => number_format($this->amount),
            'date' => $this->date,
            'madeBy' => $this->madeBy,
            'refno' => $this->refno,
            'reason' => $this->reason,
            'recordedby' => $this->user,
            'branch' => $this->branch,
            'recordedon' => Carbon::parse($this->created_at)->format("Y-m-d H:i:s a"),
        ];
    }
}
