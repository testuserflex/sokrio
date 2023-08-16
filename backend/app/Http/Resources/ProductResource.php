<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProductResource extends JsonResource
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
            'item_id' =>$this->item_id,
            'name' =>Auth::user()->branch_id==0?$this->product_name.' - '.$this->branch->name:$this->product_name,
            'name1' =>$this->unit?$this->id." | " .$this->product_name." - " .$this->product_code ." - InStock ".$this->quantity.$this->unit->symbol:$this->id." | " .$this->product_name." - S.Price ".number_format($this->selling_price),
            'name2' =>$this->unit?$this->product_name." - " .$this->product_code ." - InStock ".$this->quantity.$this->unit->symbol:$this->product_name." - S.Price ".number_format($this->selling_price),
            'name3' =>$this->unit?$this->id." | " .$this->product_name." - " .$this->product_code ." - InStock ".$this->quantity.$this->unit->symbol:$this->id." | " .$this->product_name,
            'unitname' => $this->unit?$this->unit->symbol:'',
            'code' =>$this->product_code,
            'minimum' =>$this->minimum_quantity,
            'instock' =>$this->quantity,
            'selling' =>$this->selling_price,
            'wholesale_unitprice' =>$this->wholesale_unitprice,
            'wholesale_reserveprice' =>$this->wholesale_reserveprice,
            'stock' =>$this->whenLoaded('stock')??0,
            'reserve' =>$this->reserve_price,
            'vat' =>$this->vat,
            'added_qty' =>"",
            'type' =>$this->is_product,
            'business_product' => new BusinessProductResource($this->whenLoaded('businessproduct')),
            'category' => $this->businessproduct?$this->businessproduct->category:'...',
            'product_category' => $this->businessproduct?$this->businessproduct->category->name:'...',
            'user' => $this->whenLoaded('user'),
            'baseunit' => $this->unit?$this->unit:null,
            'units' => $this->productunits?ProductUnitResource::collection($this->whenLoaded('productunits')):null,
            'branch' => $this->whenLoaded('branch'),
            'added_on' =>Carbon::parse($this->created_at)->format("Y-m-d H:i:s a")

        ];
    }
}
