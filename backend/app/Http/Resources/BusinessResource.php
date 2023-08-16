<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class BusinessResource extends JsonResource
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
            'status' => $this->status,
            'store' => $this->store,
            'address' => $this->address,
            'website' => $this->website,
            'email' => $this->email,
            'Businesslogo' => $this->logo?asset($this->logo):'',
            'phone1' => $this->phone1,
            'phone2' => $this->phone2,
            'tin' => $this->tin,
            'user' => $this->user,
            'country' => new CountryResource($this->country),
            'currency' => new CountryResource($this->currency),
            'dateAdded' => Carbon::parse($this->created_at)->format('d/m/Y | h:i A'),
        ];
    }
}
