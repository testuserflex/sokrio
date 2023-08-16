<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{

    public function getCategory($id){
        if($id == 1){
            return 'Sale';
        }else if($id == 2){
            return 'Expense';
        }else if($id == 3){
            return 'Purchase';
        }else if($id == 4){
            return 'Sale return';
        }else if($id == 5){
            return 'Purchase return';
        }else if($id == 6){
            return 'Cashout';
        }else if($id == 7){
            return 'Cashin';
        }else if($id == 8){
            return 'Money transfer';
        }else if($id == 9){
            return 'Customer deposit';
        }
    }
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
            'category' =>$this->getCategory($this->category),
            'amount_in' =>$this->amount_in,
            'amount_out' =>$this->amount_out,
            'modeId' => $this->mode,
            'mode' => $this->modex?$this->modex->type_name." - ".$this->modex->name."(".$this->modex->account_number.")":null,
            'date' => $this->date,
            'description' => $this->description,
            'recordedby' =>$this->user?$this->user->name:'',
            'branch' => $this->branch?$this->branch->name:'',
            'recordedon' => Carbon::parse($this->created_at)->format("Y-m-d H:i:s a"),
        ];
    }
}
