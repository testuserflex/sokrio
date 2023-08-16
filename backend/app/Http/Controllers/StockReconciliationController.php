<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Http\Resources\ReconcilationResource;
use App\Models\Log;
use App\Models\Product;
use App\Models\Reconcilation;
use App\Models\ReconcilationLog;
use App\Models\Stock;
use App\Models\ProductUnit;
use App\Models\StockMovement;
use App\Models\StockReconciliation;
use App\Models\Store;
use App\Models\StoreMovement;
use App\Models\Notification;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockReconciliationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->branch_id==0){
            $reconciliations = Reconcilation::where('business_id', Auth::user()->business_id)->get();
        }
        else{
            $reconciliations = Reconcilation::where('branch_id', Auth::user()->branch_id)->get();
        }
        return ReconcilationResource::collection($reconciliations);
    }

    public function show($id){
        $reconcile = Reconcilation::with(['items','branch'])->find($id);
        return new ReconcilationResource($reconcile);
    }

    public function update(Request $request, $id)
    {
        // return $request;
        $request->validate([
            'id' => 'required|integer',
            'unit_id' => 'required|integer',
            'added_qty' => 'required',
            'type' => 'required',
        ]);
        if ($request->type == 0){
            $pdtunit = ProductUnit::where('branch_id', Auth::user()->branch_id)->where('id',$request->id)->where('unit_id', $request->unit_id)->first();
            // return $pdtunit;
            $pdtunit->stock_movement = $request->added_qty;
            $pdtunit->save();
            $pdts = ProductUnit::where('branch_id', Auth::user()->branch_id)->where('product_id',$pdtunit->product_id)->get();
            $actual_qty = 0;
            foreach($pdts as $punits){
                $actual_qty = $actual_qty+($punits->stock_movement*$punits->base_quantity);
            }
            $stockqty = Stock::where('product_id',$pdtunit->product_id)->where('branch_id',Auth::user()->branch_id)->first();
            $newstock = $stockqty->update(['actual_quantity'=>$actual_qty]);
        }else{
            $pdtunit = ProductUnit::where('branch_id', Auth::user()->branch_id)->where('id',$request->id)->where('unit_id', $request->unit_id)->first();
            $unitqty = $pdtunit->base_quantity;
            $pdtunit->stock_movement = $request->added_qty;
            $pdtunit->save();
            $pdts = ProductUnit::where('branch_id', Auth::user()->branch_id)->where('product_id',$pdtunit->product_id)->get();
            $actual_qty = 0;
            foreach($pdts as $punits){
                $actual_qty = $actual_qty+($punits->stock_movement*$punits->base_quantity);
            }
            $up = Store::where('product_id',$pdtunit->product_id)->where('branch_id',Auth::user()->branch_id)->first();
            $up->update(['actual_quantity'=>$actual_qty]);
        }
    }


    public function reconcile(Request $request)
    {
        if ($request->type == 0) {
            $stocks = Stock::where('branch_id', Auth::user()->branch_id)->whereNotNull('actual_quantity')->get();
            if (count($stocks)>0) {

                $reconcile = Reconcilation::create([
                    'num' => count($stocks),
                    'type' => 0,
                    'user_id' => Auth::user()->id,
                    'branch_id' => Auth::user()->branch_id,
                    'business_id' => Auth::user()->business_id
                ]);
                foreach ($stocks as $stock) {
                    $quantity = $stock->quantity;
                    $actual = $stock->actual_quantity;
                    $diff = $quantity - $actual;

                    if ($quantity != $actual) {
                        $reconciled = StockReconciliation::create([
                            'rec_date' => date('Y-m-d'),
                            'from' => $quantity,
                            'to' => $actual,
                            'recId' => $reconcile->id,
                            'product_id' => $stock->product_id,
                            'buying_price'=>round($stock->buying_price,2),
                            'user_id' => Auth::user()->id,
                            'branch_id' => Auth::user()->branch_id,
                            'business_id' => Auth::user()->business_id,
                        ]);
                        $pdtunit = ProductUnit::where('branch_id', Auth::user()->branch_id)->where('product_id',$stock->product_id)->where('stock_movement', '>', 0)->get();
                        foreach($pdtunit as $product){
                        $reconcilelog = ReconcilationLog::create([
                            'product_id' => $product->product_id,
                            'unit_id' => $product->unit_id,
                            'stockreconciliation_id' => $reconciled->id,
                            'base_quantity' => $product->base_quantity,
                            'type' => 0,
                            'actual_quantity' => $product->stock_movement,
                            'branch_id' => Auth::user()->branch_id,
                            'business_id' => Auth::user()->business_id
                        ]);
                        }
                        $stock->update(['actual_quantity' => Null]);

                        $pdt = Product::where('id', $stock->product_id)->where('branch_id', Auth::user()->branch_id)->first();
                        $pdt->quantity = $actual;
                        $pdt->save();

                        // Update stock qty
                        Stock::where('product_id', $stock->product_id)->where('branch_id', Auth::user()->branch_id)->update(["quantity" => $actual]);


                        if ($diff < 0) {
                            app('App\Http\Controllers\FunctionController')
                                ->stockmove($stock->product_id, StockMovement::Reconciled, -$diff, 0,  $reconciled->id);
                        } else {
                            app('App\Http\Controllers\FunctionController')
                                ->stockmove($stock->product_id, StockMovement::Reconciled, 0, $diff, $reconciled->id);
                        }

                        if($pdt->minimum_quantity >= $actual){
                            Notification::create([
                                'shortmessage' => "Product $pdt->name is running out of Stock",
                                'message' => "Product $pdt->name is running out of Stock, current stock level is $actual. Please restock",
                                'type' => 1,
                                'branch_id' => Auth::user()->branch_id,
                                'business_id' => Auth::user()->business_id

                            ]);
                        }
                    }

                }
                $action = "Reconciled Stock";
                app('App\Http\Controllers\FunctionController')->log($action, Log::Stock);

            }else{
                return response(['message'=>'No Items to reconcile'],201);
            }
        }else{
            $stocks = Store::where('branch_id', Auth::user()->branch_id)->whereNotNull('actual_quantity')->get();
            if (count($stocks)>0) {
                $reconcile = Reconcilation::create([
                    'num' => count($stocks),
                    'type' => 1,
                    'user_id' => Auth::user()->id,
                    'branch_id' => Auth::user()->branch_id,
                    'business_id' => Auth::user()->business_id
                ]);
                foreach ($stocks as $stock) {
                    $quantity = $stock->quantity;
                    $actual = $stock->actual_quantity;
                    $diff = $quantity - $actual;
                    if ($quantity != $actual) {
                        $reconciled = StockReconciliation::create([
                            'rec_date' => date('Y-m-d'),
                            'from' => $quantity,
                            'to' => $actual,
                            'recId' => $reconcile->id,
                            'product_id' => $stock->product_id,
                            'buying_price'=>round($stock->buying_price,2),
                            'user_id' => Auth::user()->id,
                            'branch_id' => Auth::user()->branch_id,
                            'business_id' => Auth::user()->business_id,
                        ]);
                        $pdtunit = ProductUnit::where('branch_id', Auth::user()->branch_id)->where('product_id',$stock->product_id)->where('stock_movement', '>', 0)->get();
                        foreach($pdtunit as $product){
                        $reconcilelog = ReconcilationLog::create([
                            'product_id' => $product->product_id,
                            'unit_id' => $product->unit_id,
                            'stockreconciliation_id' => $reconciled->id,
                            'base_quantity' => $product->base_quantity,
                            'type' => 1,
                            'actual_quantity' => $product->stock_movement,
                            'branch_id' => Auth::user()->branch_id,
                            'business_id' => Auth::user()->business_id
                        ]);
                        }
                        $stock->update(['actual_quantity' => Null]);
                        if ($diff > 0) {
                            // record store movement
                            StoreMovement::create([
                                'product_id' => $reconciled->product_id,
                                'quantity_in' => $diff,
                                'quantity_out' => 0,
                                'date' => date('Y-m-d'),
                                'type' => 1,
                                'transaction_id' => $reconciled->id,
                                'branch_id' => Auth::user()->branch_id,
                                'business_id' => Auth::user()->business_id,
                                'user_id' => Auth::user()->id,
                            ]);
                        } else {
                            // record store movement
                            StoreMovement::create([
                                'product_id' => $reconciled->product_id,
                                'quantity_in' => 0,
                                'quantity_out' => $diff,
                                'date' => date('Y-m-d'),
                                'type' => 1,
                                'transaction_id' => $reconciled->id,
                                'branch_id' => Auth::user()->branch_id,
                                'business_id' => Auth::user()->business_id,
                                'user_id' => Auth::user()->id,
                            ]);
                        }
                        // update store quantity
                        Store::where('product_id', $stock->product_id)->where('branch_id', Auth::user()->branch_id)->update(["quantity" => $actual]);

                    }

                }
                $action = "Reconciled Store Stock";
                app('App\Http\Controllers\FunctionController')->log($action, Log::Stock);
            }else{
                return response(['message'=>'No Items to reconcile'],201);
            }
        }
    }
}
