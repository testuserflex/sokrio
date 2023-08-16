<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class LogResource extends JsonResource
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
            'activity' => $this->activity,
            'type' => $this->category,
            'branch' => $this->branch->name,
            'date' => Carbon::parse($this->created_at)->format("Y-m-d"),
            'createddate' => Carbon::parse($this->created_at)->format("Y-m-d H:i:s a"),
            'user' => $this->user?$this->user->name:'',
        ];
    }
}
