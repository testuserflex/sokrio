<?php

namespace App\Http\Resources;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class BusinessProductResource extends JsonResource
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
            'name' =>$this->name,
            'category' => new ProductCategoryResource($this->category),
            'user' => $this->whenLoaded('user'),
            'added_on' =>Carbon::parse($this->created_at)->format("Y-m-d H:i:s a")

        ];
    }
}
