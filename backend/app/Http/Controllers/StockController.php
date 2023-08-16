<?php

namespace App\Http\Controllers;

use App\Http\Resources\BatchResource;
use App\Http\Resources\StockMovementResource;
use App\Models\Batch;
use App\Models\Product;
use App\Models\SpoiltStock;
use App\Models\PriceChange;
use App\Models\SellingpriceChange;
use App\Models\Stock;
use App\Models\ProductUnit;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{

    public function updateBuyingPrice(Request $request, $id){
        $stock=Stock::where('product_id',$id)->where('branch_id',Auth::user()->branch_id)->first();
        if($stock->buying_price != str_replace(",","",$request->buying)){
            $stock->update([
                'buying_price'=>str_replace(",","",$request->buying),
                'average_buyingprice'=>str_replace(",","",$request->buying),
            ]);
            $pricechange = PriceChange::create([
                'product_id' => $id,
                'old_price' => $stock->buying_price,
                'new_price' => str_replace(",","",$request->buying),
                'type' => 0,
                'user_id' => Auth::user()->id,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
            ]);
        }
    }


    public function EditStock(Request $request, $id){
        $unit=ProductUnit::where('id',$id)->where('branch_id',Auth::user()->branch_id)->first();
        if($request->wholesale_reserveprice){
            $wholesale_reserve = str_replace(",","",$request->wholesale_reserveprice);
        }else{
            $wholesale_reserve = $request->wholesale_unitprice?str_replace(",","",$request->wholesale_unitprice):0;
        }

        $unit->update([
            'selling_price' => str_replace(",","",$request->selling_price),
            'reserve_price' => $request->reserve_price?str_replace(",","",$request->reserve_price):str_replace(",","",$request->selling_price),
            'wholesale_unitprice' => $request->wholesale_unitprice?str_replace(",","",$request->wholesale_unitprice):0,
            'wholesale_reserveprice' => $wholesale_reserve,
        ]);

        if($unit->selling_price != str_replace(",","",$request->selling_price)){
            $pricechange = SellingpriceChange::create([
                'product_id' => $unit->product_id,
                'old_price' => $unit->selling_price,
                'new_price' => $request->selling_price,
                'user_id' => Auth::user()->id,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
            ]);
        }
        if($unit->is_base == 1){
            $product=Product::where('id',$unit->product_id)->where('branch_id',Auth::user()->branch_id)->update([
                'selling_price' => str_replace(",","",$request->selling_price),
                'reserve_price' => $request->reserve_price?str_replace(",","",$request->reserve_price):str_replace(",","",$request->selling_price),
                'wholesale_unitprice' => $request->wholesale_unitprice?str_replace(",","",$request->wholesale_unitprice):0,
                'wholesale_reserveprice' => $wholesale_reserve,
            ]);
        }
    }


    public function stockMovement($id){
        $stockmove = StockMovement::where('branch_id',Auth::user()->branch_id)->where('product_id',$id)->limit(2000)->orderBy("created_at", "desc")->get();
        return StockMovementResource::collection($stockmove);
    }

    public function shortExpiry(){
        $date = date('Y-m-d');
        if(Auth::user()->branch_id == 0){
            $short = Batch::with('branch')->where('business_id',Auth::user()->business_id)->where('is_expired',Batch::Not_expired)->where('quantity_in','>','quantity_out')
            ->where('expiry_date','<=',date('Y-m-d', strtotime($date. ' + 90 days')))->where('expiry_date','>=',$date)->get();
        }else{
            $short = Batch::with('branch')->where('branch_id',Auth::user()->branch_id)->where('is_expired',Batch::Not_expired)->where('quantity_in','>','quantity_out')
            ->where('expiry_date','<=',date('Y-m-d', strtotime($date. ' + 90 days')))->where('expiry_date','>=',$date)->get();
        }
        return BatchResource::collection($short);
    }

    public function expired(){
        $date = date('Y-m-d');
        if(Auth::user()->branch_id == 0){
            $expired = Batch::with('branch')->where('business_id',Auth::user()->business_id)->where('expiry_date','<',$date)
            ->whereNull('quantity_expired')->where('quantity_in','>','quantity_out')->get();
        }else{
            $expired = Batch::with('branch')->where('branch_id',Auth::user()->branch_id)->where('expiry_date','<',$date)
            ->whereNull('quantity_expired')->where('quantity_in','>','quantity_out')->get();
        }
        return BatchResource::collection($expired);
    }

    public function removed(){
        $removed = Batch::where('branch_id',Auth::user()->branch_id)->where('is_expired',Batch::Removed)
        ->where('quantity_expired','>',0)->get();
        return BatchResource::collection($removed);
    }

    public function markExpired(Request $request){
        $request->validate(['id' => 'required']);
        Batch::where('id',$request->id)->where('branch_id',Auth::user()->branch_id)->update(['is_expired'=>2,'quantity_expired'=>$request->qty??0,]);
        if($request->status==1){
            $splt = SpoiltStock::create([
                'product_id' =>$request->product,
                'reason' =>"Expired",
                'unit_buying_price' => Stock::where('product_id',$request->product)->first()->buying_price ?? 0,
                'quantity' => $request->qty,
                'batch' => $request->id,
                'expired' => 1,
                'date_removed' => date('Y-m-d'),
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
                'user_id' => Auth::user()->id,
            ]);

            // Update product qty
            app('App\Http\Controllers\FunctionController')->updateStockLevel($splt->product_id,-($request->qty));

             // insert into stock movement
             app('App\Http\Controllers\FunctionController')->stockmove($request->product,StockMovement::SpoiltStock,0,$request->qty,$splt->id);

        }

    }
}
