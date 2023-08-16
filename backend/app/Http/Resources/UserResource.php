<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'names' => $this->name,
            'uemail' => $this->email,
            'phone1' => $this->contact,
            'role' => $this->rolex,
            'uname' => $this->username,
            'status' => $this->status,
            'user' => $this->user,
            'initial_visit' => $this->initial_visit,
            'branch' => new BranchResource($this->branch),
            'branch_name' => $this->branch?$this->branch->name:'',
            'branch_id' => $this->branch_id,
            'business' => new BusinessResource($this->business),
            'business_name' => $this->business->name,
            'dateAdded' => Carbon::parse($this->created_at)->format('d/m/Y | h:i A'),
        ];
    }
}
