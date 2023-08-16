<?php

namespace App\Http\Resources;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentOptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public function Totals($id){
        return Transaction::where('mode', $id)->sum(DB::raw('ROUND(amount_in - amount_out, 2)'));
    }
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'mode' => $this->type_name." - ".$this->name."(".$this->account_number.")",
            'type' => $this->type,
            'anumber' => $this->account_number,
            'type_name' => $this->type_name,
            'default' => $this->is_default,
            'balance' => number_format($this->Totals($this->id)),
            'addedby' => $this->user,
            'branch' => $this->branch,
            'dateAdded' => Carbon::parse($this->created_at)->format("Y-m-d H:i:s a")
        ];
    }
}
