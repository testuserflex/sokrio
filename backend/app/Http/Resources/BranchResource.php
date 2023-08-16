<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
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
            'address' => $this->address,
            'email' => $this->email,
            'phone1' => $this->phone1,
            'phone2' => $this->phone2,
            'status' => $this->status,
            'upload' => $this->upload,
            'user' => $this->whenLoaded('user'),
            'country' => new CountryResource($this->country),
            'currency' => new CountryResource($this->currency),
            'dateAdded' => Carbon::parse($this->created_at)->format('d/m/Y | h:i A'),
        ];
    }
}
