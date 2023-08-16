<?php

namespace App\Http\Controllers;

use App\Http\Resources\PurchaseReturnResource;
use App\Models\Batch;
use App\Models\Log;
use App\Models\Product;
use App\Models\PurchaseDetail;
use App\Models\PurchaseReturn;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\Store;
use App\Models\StoreMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->branch_id==0){
            $returns = PurchaseReturn::where('business_id',Auth::user()->business_id)->get();
        }
        else{
            $returns = PurchaseReturn::where('branch_id',Auth::user()->branch_id)->get();
        }
        return PurchaseReturnResource::collection($returns);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'purchase_id' => 'required',
            'product' => 'required',
            'quantity' => 'required',
        ]);
        if($request->quantity>0){
            $purchase = PurchaseDetail::with('purchase')->where('purchase_id',$request->purchase_id)->where('product_id',$request->product)
            ->where('branch_id',Auth::user()->branch_id)->first();
            $unitPrice = $purchase->unit_buying_price;
            $batch = $purchase->batch;
            $type = $purchase->purchase->category;
            if($type == '0'){
                $qtyinstk = Stock::where('product_id',$request->product)->where('branch_id', Auth::user()->branch_id)->first()->quantity;
                if($request->quantity <= $qtyinstk){
                    // update batch
                    if($batch!=''){
                        $batchdet = Batch::where('batch_number',$batch)->where('product_id',$request->product)->where('branch_id',Auth::user()->branch_id)
                        ->where('type',0)->first();
                        $batchdet->quantity_out = $batchdet->quantity_out+$request->quantity;
                        $batchdet->save();
                    }

                    // record purchase return
                    $returnrecord = PurchaseReturn::create([
                        'purchase_id' => $request->purchase_id,
                        'product_id' => $request->product,
                        'quantity' => $request->quantity,
                        'unit_buying_price' => $unitPrice,
                        'amount_refunded' => str_replace(' ', '', $request->amount),
                        'mode' => $request->mode,
                        'type' =>0,
                        'date' => $request->date ?? date('Y-m-d'),
                        'reason' => $request->reason,
                        'batch' => $batch,
                    ]);

                    // Update purchase return status to 1
                    $purchase->update(['return_status', 1]);

                    if($request->quantity>0){
                        // insert into transactions
                        app('App\Http\Controllers\FunctionController')->transaction(5,$returnrecord->id,$returnrecord->amount_refunded,0,$returnrecord->mode,"Purchase Return");
                    }
                    // Update product quantity
                    app('App\Http\Controllers\FunctionController')->updateStockLevel($returnrecord->product_id,-($returnrecord->quantity));
                    // insert into stock movement
                    app('App\Http\Controllers\FunctionController')->stockmove($returnrecord->product_id,StockMovement::PurchaseReturn,0,$returnrecord->quantity,$returnrecord->id);
                    // insert into logs
                    $action = "Recorded a purchase return of product ".Product::find($request->product)->product_name;
                    app('App\Http\Controllers\FunctionController')->log($action, Log::Stock);
                }
            }
            else{
                $str = Store::where('product_id',$request->product)->where('branch_id', Auth::user()->branch_id)->first();
                $qtyinstk = $str->quantity;
                if($request->quantity <= $qtyinstk){
                    // update batch
                    if($batch!=''){
                        $batchdet = Batch::where('batch_number',$batch)->where('product_id',$request->product)->where('branch_id',Auth::user()->branch_id)
                        ->where('type',1)->first();
                        $batchdet->quantity_out = $batchdet->quantity_out+$request->quantity;
                        $batchdet->save();
                    }

                    // record purchase return
                    $returnrecord = PurchaseReturn::create([
                        'purchase_id' => $request->purchase_id,
                        'product_id' => $request->product,
                        'quantity' => $request->quantity,
                        'unit_buying_price' => $unitPrice,
                        'amount_refunded' => str_replace(' ', '', $request->amount),
                        'mode' => $request->mode,
                        'type' =>1,
                        'date' => $request->date ?? date('Y-m-d'),
                        'reason' => $request->reason,
                        'batch' => $batch,
                    ]);

                    // Update purchase return status to 1
                    $purchase->update(['return_status', 1]);
                    // update store quantity
                    app('App\Http\Controllers\FunctionController')->updateStoreLevel($returnrecord->product_id, -($returnrecord->quantity));

                    if(str_replace(' ', '', $request->amount)>0){
                        // insert into transactions
                        app('App\Http\Controllers\FunctionController')->transaction(5,$returnrecord->id,$returnrecord->amount_refunded,0,$returnrecord->mode,"Purchase Return");
                    }
                    // insert into store movement
                    StoreMovement::create([
                        'product_id' =>$returnrecord->product_id,
                        'quantity_in' => 0,
                        'quantity_out' => str_replace(",","",$returnrecord->quantity),
                        'date' => date('Y-m-d'),
                        'type' => 3,
                        'transaction_id' => $returnrecord->id,
                        'description' => "Store purchase return",
                        'branch_id' => Auth::user()->branch_id,
                        'business_id' => Auth::user()->business_id,
                        'user_id' => Auth::user()->id,
                    ]);

                    // insert into logs
                    $action = "Recorded a purchase return of product ".Product::find($request->product)->product_name;
                    app('App\Http\Controllers\FunctionController')->log($action, Log::Stock);
            }
        }

    }
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {


  }
}
