<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AccessTimeResource extends JsonResource
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
            'user' =>$this->user->name,
            'from_time' => $this->from_time,
            'to_time' => $this->to_time,
            'mon' => $this->mon,
            'tue' => $this->tue,
            'wed' => $this->wed,
            'thu' => $this->thu,
            'fri' => $this->fri,
            'sat' => $this->sat,
            'sun' => $this->sun,
            'updatedby' => $this->updatedby->name,
            'branch' => $this->branch,
            'recordedon' => Carbon::parse($this->created_at)->format("Y-m-d H:i:s a"),
        ];
    }
}
