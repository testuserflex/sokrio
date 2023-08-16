<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class PaymentResource extends JsonResource
{

    function getCustomer($id){
        $Customer = Customer::where('id',$id)->where('branch_id', Auth::user()->branch_id)->first();
        if( $Customer){
            return $Customer->name;
        }else{
            return 'Customer not given';
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
            'id' => $this->id,
            'sale' => $this->sale,
            'customer' => $this->getCustomer($this->sale->customer_id),
            'amount' => $this->amount,
            'date' => $this->date,
            'mode' => $this->modex?$this->modex->type_name." - ".$this->modex->name."(".$this->modex->account_number.")":null,
            'receipt' => $this->receipt,
            'recordedby' => $this->user?$this->user->name:'',
            'branch' => $this->branch->name,
            'business' => $this->business->name,
            'recordedon' => Carbon::parse($this->created_at)->format("Y-m-d H:i:s a"),
        ];
    }
}
