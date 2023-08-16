<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
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
            'date' => $this->date,
            'amount' => $this->amount,
            'category' => new ExpenseCategoryResource($this->category),
            'user' => new UserResource($this->user),
            'branch' => $this->branch,
            'mode' => $this->mode,
            'description' => $this->description,
            'dateAdded' => Carbon::parse($this->created_at)->format("Y-m-d H:i:s a"),

        ];
    }
}
