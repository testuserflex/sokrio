<?php

namespace App\Http\Resources;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleDetailResource extends JsonResource
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
            'saleId' => $this->sale_id,
            'sale' => $this->sale,
            'receipt' => $this->sale->transaction_status==2 && $this->sale->offline_receipt?$this->sale->offline_receipt:$this->sale->receipt,
            'product' => $this->product->product_name,
            'category' => $this->product->businessproduct?$this->product->businessproduct->category_id:'',
            'product_id' => $this->product_id,
            'usymbol' => $this->punit?$this->punit->unit->symbol:null,
            'BPrice' => GeneralSetting::where('branch_id', Auth::user()->branch_id)->first()->use_last_costprice == 1?round($this->buying_price, 2):round($this->average_buyingprice, 2),
            'BPrice_no' => GeneralSetting::where('branch_id', Auth::user()->branch_id)->first()->use_last_costprice == 1?number_format(round($this->buying_price, 2)):number_format(round($this->average_buyingprice, 2)),
            'SPrice' => round($this->selling_price, 2),
            'SPrice_no' => number_format(round($this->selling_price, 2)),
            'qty' => $this->quantity,
            'total_price' => number_format($this->quantity*$this->selling_price),
            'total_priceno' => round($this->quantity*$this->selling_price, 2),
            'total_bpriceno' => GeneralSetting::where('branch_id', Auth::user()->branch_id)->first()->use_last_costprice == 1?round($this->quantity*$this->buying_price, 2):round($this->quantity*$this->average_buyingprice, 2),
            'total_bprice' => GeneralSetting::where('branch_id', Auth::user()->branch_id)->first()->use_last_costprice == 1?number_format(round($this->quantity*$this->buying_price, 2)):number_format(round($this->quantity*$this->average_buyingprice, 2)),
            'profit' => GeneralSetting::where('branch_id', Auth::user()->branch_id)->first()->use_last_costprice == 1?round(($this->quantity*$this->selling_price)-($this->quantity*$this->buying_price), 2):round(($this->quantity*$this->selling_price)-($this->quantity*$this->average_buyingprice), 2),
            'qty_remaining' => $this->quantity_remaining,
            'batch' => $this->batch,
            'description' => $this->description??'',
            'picking_date' => $this->sale->picking_date??'',
            'user' => $this->user,
            'Returned' => $this->returned,
            'Picked' => $this->sale->picked,
            'branch' => $this->branch->name,
            'DatePicked' => $this->sale->date_picked,
            'discount' => $this->sale->discount
        ];
    }
}
