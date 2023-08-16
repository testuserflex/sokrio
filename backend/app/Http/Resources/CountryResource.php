<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
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
            'name' => $this->country_name,
            'dial_cod' => $this->dial_code,
            'code' => $this->country_code,
            'currency_code' => $this->currency_code,
            'currency_name' => $this->currency_name,
        ];
    }
}
