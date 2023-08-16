<?php

namespace App\Http\Controllers;

use App\Http\Resources\PurchaseResource;
use App\Http\Resources\PurchaseDetailResource;
use App\Http\Resources\PriceChangeResource;
use App\Http\Resources\CreditorResource;
use App\Http\Resources\PurchasePaymentResource;
use App\Models\Batch;
use App\Models\ProductUnit;
use App\Models\Log;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\PurchaseCart;
use App\Models\PurchaseDetail;
use App\Models\PurchasePayment;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\Store;
use App\Models\PriceChange;
use App\Models\StoreMovement;
use App\Models\SupplierAccount;
use App\Models\Transaction;
use App\Models\SellingpriceChange;
use App\Models\GeneralSetting;
use App\Models\Creditor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShopPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        if(Auth::user()->branch_id==0){
            $purchases = Purchase::where('business_id', Auth::user()->business_id)->orderBy("created_at", "desc")->get();
        }
        else{
            $purchases = Purchase::where('branch_id', Auth::user()->branch_id)->orderBy("created_at", "desc")->get();
        }
        return PurchaseResource::collection($purchases);
    }


    // Price Changes
    public function shopprice_change()
    {
        if(Auth::user()->branch_id==0){
            $pricechange = PriceChange::where('business_id', Auth::user()->business_id)->where('type',0)->orderBy("created_at", "desc")->get();
        }
        else{
            $pricechange = PriceChange::where('branch_id', Auth::user()->branch_id)->where('type',0)->orderBy("created_at", "desc")->get();
        }
        return PriceChangeResource::collection($pricechange);
    }


    public function purchase_payments()
    {
        if(Auth::user()->branch_id==0){
            $purchases_payments = PurchasePayment::where('business_id', Auth::user()->business_id)->orderBy("created_at", "desc")->get();
        }
        else{
            $purchases_payments = PurchasePayment::where('branch_id', Auth::user()->branch_id)->orderBy("created_at", "desc")->get();
        }
        return PurchasePaymentResource::collection($purchases_payments);
    }

    public function purchases_report(Request $request)
    {
        if(Auth::user()->branch_id==0){
            if($request->product != '' && $request->branch_id != 0){
                $purchases_report = PurchaseDetail::where('branch_id', $request->branch_id)->whereHas('purchase', function ($query) use($request){
                    return $query ->where("category", 0)->whereBetween("date", [$request->datefrom, $request->dateto]);
                })->whereHas('product', function ($query) use($request) {
                    return $query->where('id', $request->product);
                })->with('purchase')->orderBy("created_at", "desc")->get();
            }elseif($request->product == '' && $request->branch_id != 0){
                $purchases_report = PurchaseDetail::where('branch_id', $request->branch_id)->whereHas('purchase', function ($query) use($request){
                    return $query ->where("category", 0)->whereBetween("date", [$request->datefrom, $request->dateto]);
                })->with('purchase')->orderBy("created_at", "desc")->get();
            }elseif($request->product != '' && $request->branch_id == 0){
                $purchases_report = PurchaseDetail::where('business_id', Auth::user()->business_id)->whereHas('purchase', function ($query) use($request){
                    return $query ->where("category", 0)->whereBetween("date", [$request->datefrom, $request->dateto]);
                })->whereHas('product', function ($query) use($request) {
                    return $query->where('id', $request->product);
                })->with('purchase')->orderBy("created_at", "desc")->get();
            }else{
                $purchases_report = PurchaseDetail::where('business_id', Auth::user()->business_id)->whereHas('purchase', function ($query) use($request){
                    return $query ->where("category", 0)->whereBetween("date", [$request->datefrom, $request->dateto]);
                })->with('purchase')->orderBy("created_at", "desc")->get();
            }
        }
        else{
            if($request->product == ''){
                $purchases_report = PurchaseDetail::where('branch_id', Auth::user()->branch_id)->whereHas('purchase', function ($query) use($request){
                    return $query ->where("category", 0)->whereBetween("date", [$request->datefrom, $request->dateto]);
                })->with('purchase')->orderBy("created_at", "desc")->get();
            }else{
                $purchases_report = PurchaseDetail::where('branch_id', Auth::user()->branch_id)->whereHas('purchase', function ($query) use($request){
                    return $query ->where("category", 0)->whereBetween("date", [$request->datefrom, $request->dateto]);
                })->whereHas('product', function ($query) use($request) {
                    return $query->where('id', $request->product);
                })->with('purchase')->orderBy("created_at", "desc")->get();
            }
        }
        return PurchaseDetailResource::collection($purchases_report);
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
        $cart = PurchaseCart::where('user_id', Auth::user()->id)->where('branch_id', Auth::user()->branch_id)->where('category',0)->orderBy("created_at", "desc")->get();

        if($cart->count() > 0){

            try {
                $newtot = str_replace(",","",$request->total)-str_replace(",","",$request->discount);
                $source = PurchaseCart::where('user_id', Auth::user()->id)->where('branch_id', Auth::user()->branch_id)->where('category',0)->first()->source;
                if($source==0){
                    if($request->supplier && $newtot>str_replace(",","",$request->paid)){
                        $bal = app('App\Http\Controllers\FunctionController')->supplierBalance($request->supplier);
                        if($bal<0 && $bal<= -($newtot-str_replace(",","",$request->paid))){
                            $paid = $newtot;
                        }
                        else if($bal<0 && $bal> -($newtot-str_replace(",","",$request->paid))){
                            $paid = str_replace(",","",$request->paid)-$bal;
                        }
                        else{
                            $paid = str_replace(",","",$request->paid);
                        }

                    }
                    else{
                        $paid = str_replace(",","",$request->paid);
                    }
                    $purchase = Purchase::create([
                        'receipt' => time(),
                        'total' => $newtot,
                        'paid' => $paid ?? 0,
                        'discount' => str_replace(",","",$request->discount) ?? 0,
                        'supplier_id' => $request->supplier,
                        'category' =>0,
                        'date' => $request->date ?? date('Y-m-d'),
                        'user_id' => Auth::user()->id,
                        'branch_id' => Auth::user()->branch_id,
                        'business_id' => Auth::user()->business_id,
                    ]);

                    $no = 0;
                    foreach ($cart as $item){
                        $no++;
                        $pd = PurchaseDetail::create([
                            'product_id' => $item->product_id,
                            'quantity' => $item->quantity,
                            'buying_price' => $item->unit_price,
                            'selling_price' => $item->unit_sellingprice,
                            'expiry' => $item->expiry,
                            'batch' => $item->batch,
                            'unit' => $item->unit,
                            'category' => 0,
                            'purchase_id' => $purchase->id,
                            'user_id' => Auth::user()->id,
                            'branch_id' => Auth::user()->branch_id,
                            'business_id' => Auth::user()->business_id,
                        ]);

                        $productprice = ProductUnit::where('product_id',$item->product_id)->where('branch_id',Auth::user()->branch_id)->where('id',$item->unit)->first();
                        if($productprice['selling_price'] != $item->unit_sellingprice){
                            $pricechange = SellingpriceChange::create([
                                'product_id' => $item->product_id,
                                'unit_id' => $item->unit,
                                'old_price' => $productprice['selling_price'],
                                'new_price' => $item->unit_sellingprice,
                                'user_id' => Auth::user()->id,
                                'branch_id' => Auth::user()->branch_id,
                                'business_id' => Auth::user()->business_id,
                            ]);
                            $productprice['selling_price'] = $item->unit_sellingprice;
                            $productprice->save();
                            if($productprice['base_quantity'] == 1){
                                Product::find($item->product_id)->update(['selling_price' => $item->unit_sellingprice]);
                            }
                        }
                        $unitqty = ProductUnit::find($item->unit)->base_quantity;

                        if($item->batch && $item->batch!='' && $item->expiry && $item->expiry!=''){
                            $batchcheck = Batch::where('batch_number',$pd->batch)->where('product_id',$pd->product_id)->where('type',0)
                            ->where('branch_id', Auth::user()->branch_id)->first();
                            if($batchcheck){
                                $batchcheck->quantity_in = $batchcheck->quantity_in+($pd->quantity*$unitqty);
                                $batchcheck->save();
                            }
                            else{
                                Batch::create([
                                    'batch_number' => $pd->batch,
                                    'expiry_date' => $pd->expiry,
                                    'quantity_in' => $pd->quantity*$unitqty,
                                    'quantity_out' => 0,
                                    'type' =>0,
                                    'product_id' => $pd->product_id,
                                    'user_id' => Auth::user()->id,
                                    'branch_id' => Auth::user()->branch_id,
                                    'business_id' => Auth::user()->business_id,
                                ]);
                            }
                        }
                        // Update product quantity
                        app('App\Http\Controllers\FunctionController')->updateStockLevel($item->product_id,$item->quantity*$unitqty);
                        // Update Avg Buying Price
                        app('App\Http\Controllers\FunctionController')->getAverageStockBPrice($item->product_id,$item->quantity*$unitqty,$item->unit_price/$unitqty);

                        // insert into stock movement
                        app('App\Http\Controllers\FunctionController')->stockmove($item->product_id,StockMovement::Purchase,$item->quantity*$unitqty,0,$pd->id);

                        // Delete from cart
                        PurchaseCart::find($item->id)->delete();
                    }

                    $thismon = date("m");
                    if(strlen($thismon)<2){
                        $thismon = "0".$thismon;
                    }
                    $checklast = PurchasePayment::where('branch_id', Auth::user()->branch_id)->where('month',$thismon)
                    ->where('year',date("Y"))->first();
                    if($checklast){
                        $lrec = $checklast->recno;
                    }
                    else{
                        $lrec = 0;
                    }
                    $nrec = $lrec+1;

                    $ppayment = PurchasePayment::create([
                        'purchase_id' => $purchase->id,
                        'amount' => $purchase->paid,
                        'mode' => $request->mode,
                        'receipt' => $purchase->receipt,
                        'date' => $purchase->date,
                        'recno' => $nrec,
                        'month' => date("m"),
                        'year' => date("Y"),
                        'user_id' => $purchase->user_id,
                        'branch_id' => $purchase->branch_id,
                        'business_id' => $purchase->business_id,
                    ]);

                    if($purchase->paid>0){
                        // insert into transactions
                        app('App\Http\Controllers\FunctionController')->transaction(3,$ppayment->id,0,$ppayment->amount,$ppayment->mode,"Products Purchase",$ppayment->date);
                    }

                    if($request->supplier && $newtot>str_replace(",","",$request->paid)){
                        SupplierAccount::create([
                            'supplier_id' => $request->supplier,
                            'amount_out' => str_replace(",","",$request->paid),
                            'amount_in' => $newtot,
                            'tId' => $ppayment->id,
                            'mode' => $ppayment->mode,
                            'date' => $ppayment->date,
                            'description' => "Partial Purchase Payment for items of ".$newtot,
                            'user_id' => $ppayment->user_id,
                            'branch_id' => $ppayment->branch_id,
                            'business_id' => $ppayment->business_id,
                        ]);
                    }
                    // insert into logs
                    app('App\Http\Controllers\FunctionController')->log("Recorded a purchase of $no products", Log::Stock);
                    // Return purchase id
                    return response(['id'=> $purchase->id, 'status'=> 200]);


                }
            }catch (\Exception $e) {

                // Delete transaction
                $ppaymentId = PurchasePayment::where('purchase_id',$purchase->id)->where('branch_id',Auth::user()->branch_id)->first();
                if($ppaymentId){
                    Transaction::where("tId",$ppaymentId->id)->where('category',3)->where('branch_id',Auth::user()->branch_id)->first()?Transaction::where("tId",$ppaymentId)->where('category',3)->where('branch_id',Auth::user()->branch_id)->delete():'';
                    SupplierAccount::where("tId",$ppaymentId->id)->where('category',1)->first()?SupplierAccount::where("tId",$ppaymentId->id)->where('category',1)->delete():'';
                }

                // Delete stock movement
                StockMovement::where("transaction_id",$purchase->id)->where('type',3)->where('branch_id',Auth::user()->branch_id)->first()?StockMovement::where("transaction_id",$purchase->id)->where('type',3)->where('branch_id',Auth::user()->branch_id)->delete():'';
                // Delete Purchase
                Purchase::where('id',$purchase->id)->where('branch_id',Auth::user()->branch_id)->first()?Purchase::where('id',$purchase->id)->where('branch_id',Auth::user()->branch_id)->delete():'';
                PurchasePayment::where('purchase_id',$purchase->id)->where('branch_id',Auth::user()->branch_id)->first()?PurchasePayment::where('purchase_id',$purchase->id)->where('branch_id',Auth::user()->branch_id)->delete():'';

                $purchasedet = PurchaseDetail::where('purchase_id',$purchase->id)->where('branch_id', Auth::user()->branch_id)->get();

                if($purchasedet){
                    foreach($purchasedet as $detail){
                        $batchx = $detail->batch;
                        $product = $detail->product_id;
                        $unitqty = ProductUnit::find($detail->unit)->base_quantity;
                        $cartqty = $detail->quantity;
                        $qty = $detail->quantity*$unitqty;

                        // update batch
                        if($batchx!=''){
                            $batchdet = Batch::where('batch_number',$batchx)->where('product_id',$product)->where('branch_id',Auth::user()->branch_id)
                            ->where('type',0)->first();
                            $batchdet->quantity_out = $batchdet->quantity_out+$qty;
                            $batchdet->save();
                        }
                        // Update product quantity
                        app('App\Http\Controllers\FunctionController')->updateStockLevel($product,-($qty));
                        // Insert into cart

                        $record = PurchaseCart::where('product_id', $detail->product_id)->where('user_id', Auth::user()->id)->first();
                        if(!$record){
                            PurchaseCart::create([
                                'product_id' => $detail->product_id,
                                'quantity' => $detail->quantity,
                                'unit_price' => $detail->buying_price,
                                'unit_sellingprice' => $detail->selling_price,
                                'expiry' => $detail->expiry??'',
                                'batch' => $detail->batch??'',
                                'unit' => $detail->unit,
                                'source' => 0,
                                'category' => 0,
                                'supplier_id' => $purchase->supplier_id,
                                'user_id' => Auth::user()->id,
                                'branch_id' => Auth::user()->branch_id,
                                'business_id' => Auth::user()->business_id,
                            ]);
                        }
                        $detail->delete();
                    }
                }

                // Handle the exception or return an error response
                return response(['message' => $e], 500);
            }
        }else{
            return response(['message'=>"No items in the cart"],201);
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
        $Purchase = Purchase::find($id);
        return new PurchaseResource($Purchase);
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
        $payments = PurchasePayment::where('branch_id',Auth::user()->branch_id)->where('purchase_id',$id)->count();
        if($payments > 1){
            return response(['message'=>"This Payment has more than one payment. Delete it instead"],201);
        }
        else{
            $purchase = Purchase::where('id',$id)->where('branch_id',Auth::user()->branch_id)->first();
            $type = $purchase->category;
            $purchasedet = PurchaseDetail::where('purchase_id',$id)->where('branch_id',Auth::user()->branch_id)->get();
            if($type == '0'){
                $status = 0;
                foreach($purchasedet as $item) {
                    $product = $item->product_id;
                    $unitqty = ProductUnit::find($item->unit)->base_quantity;

                    $qty = $item->quantity * $unitqty;
                    $qtyinstk = Stock::where('product_id',$product)->where('branch_id', Auth::user()->branch_id)->first()->quantity;
                    if($qtyinstk<($qty)){
                        $status++;
                    }
                }
                $settings = GeneralSetting::where('branch_id',Auth::user()->branch_id)->first('allow_negative_stock');
                if($status>0 && $settings->allow_negative_stock == 0){
                    return response(['message'=>"The Quantity Purchased for $status products is less than the quantity in stock"],201);
                }
                else{
                    // Delete transaction
                    $ppaymentId = PurchasePayment::where('purchase_id',$id)->where('branch_id',Auth::user()->branch_id)->first()->id;
                    Transaction::where("tId",$ppaymentId)->where('category',3)->where('branch_id',Auth::user()->branch_id)->delete();
                    // Delete stock movement
                    StockMovement::where("transaction_id",$id)->where('type',3)->where('branch_id',Auth::user()->branch_id)->delete();
                    // Delete Purchase
                    Purchase::where('id',$id)->where('branch_id',Auth::user()->branch_id)->delete();
                    SupplierAccount::where("tId",$ppaymentId)->where('category',1)->delete();
                    PurchasePayment::where('purchase_id',$id)->where('branch_id',Auth::user()->branch_id)->delete();


                    $purchases = PurchaseDetail::where('purchase_id',$id)->where('branch_id',Auth::user()->branch_id)->orderBy("created_at", "asc")->get();
                    foreach($purchases as $detail){
                        $batchx = $detail->batch;
                        $product = $detail->product_id;
                        $unitqty = ProductUnit::find($detail->unit)->base_quantity;
                        $cartqty = $detail->quantity;
                        $qty = $detail->quantity*$unitqty;

                        // update batch
                        if($batchx!=''){
                            $batchdet = Batch::where('batch_number',$batchx)->where('product_id',$product)->where('branch_id',Auth::user()->branch_id)
                            ->where('type',0)->first();
                            $batchdet->quantity_out = $batchdet->quantity_out+$qty;
                            $batchdet->save();
                        }
                        // Update product quantity
                        app('App\Http\Controllers\FunctionController')->updateStockLevel($product,-($qty));
                        // Insert into cart
                        PurchaseCart::create([
                            'product_id' => $product,
                            'quantity' => $cartqty,
                            'unit_price' => $detail->buying_price,
                            'unit_sellingprice' => $detail->selling_price,
                            'expiry' => $detail->expiry??'',
                            'batch' => $detail->batch??'',
                            'unit' => $detail->unit,
                            'source' => 0,
                            'category' => 0,
                            'supplier_id' => $purchase->supplier_id,
                            'user_id' => Auth::user()->id,
                            'branch_id' => Auth::user()->branch_id,
                            'business_id' => Auth::user()->business_id,
                        ]);
                    }
                    PurchaseDetail::where('purchase_id',$id)->where('branch_id',Auth::user()->branch_id)->delete();
                    // insert into logs
                    $action = "Edited a purchase that was recorded on ".$purchase->created_at;
                    app('App\Http\Controllers\FunctionController')->log($action, Log::Stock);
                }

            }
            else{
                $status = 0;
                foreach($purchasedet as $item) {
                    $product = $item->product_id;
                    $unitqty = ProductUnit::find($item->unit)->base_quantity;
                    $qty = $item->quantity*$unitqty;
                    $qtyinstr = Store::where('product_id',$product)->where('branch_id', Auth::user()->branch_id)->first()->quantity;
                    if($qtyinstr<$qty){
                        $status++;
                    }
                }
                if($status>0){
                    return response(['message'=>"The Quantity Purchased for $status products is less than the quantity in store"],201);
                }
                else{
                    // Delete transaction
                    $ppaymentId = PurchasePayment::where('purchase_id',$id)->where('branch_id',Auth::user()->branch_id)->first()->id;
                    Transaction::where("tId",$ppaymentId)->where('category',3)->where('branch_id',Auth::user()->branch_id)->delete();
                    // Delete stock movement
                    StoreMovement::where("transaction_id",$id)->where('type',0)->where('branch_id',Auth::user()->branch_id)->delete();
                    // Delete Purchase
                    Purchase::where('id',$id)->where('branch_id',Auth::user()->branch_id)->delete();
                    PurchasePayment::where('purchase_id',$id)->where('branch_id',Auth::user()->branch_id)->delete();

                    $purchases = PurchaseDetail::where('purchase_id',$id)->where('branch_id',Auth::user()->branch_id)->get();
                    foreach($purchases as $detail){
                        $batchx = $detail->batch;
                        $product = $detail->product_id;
                        $unitqty = ProductUnit::find($detail->unit)->base_quantity;
                        $cartqty = $detail->quantity;
                        $qty = $detail->quantity*$unitqty;
                        // update batch
                        if($batchx!=''){
                            $batchdet = Batch::where('batch_number',$batchx)->where('product_id',$product)->where('branch_id',Auth::user()->branch_id)
                            ->where('type',1)->first();
                            $batchdet->quantity_out = $batchdet->quantity_out+$qty;
                            $batchdet->save();
                        }
                        // Update product quantity
                        app('App\Http\Controllers\FunctionController')->updateStoreLevel($product,-($qty));
                        // Insert into cart
                        PurchaseCart::create([
                            'product_id' => $product,
                            'quantity' => $cartqty,
                            'unit_price' => $detail->buying_price,
                            'unit_sellingprice' => $detail->selling_price,
                            'unit' => $detail->unit,
                            'expiry' => $detail->expiry??'',
                            'batch' => $detail->batch??'',
                            'category' => 1,
                            'source' => 0,
                            'supplier_id' => $purchase->supplier_id,
                            'user_id' => Auth::user()->id,
                            'branch_id' => Auth::user()->branch_id,
                            'business_id' => Auth::user()->business_id,
                        ]);
                    }
                    PurchaseDetail::where('purchase_id',$id)->where('branch_id',Auth::user()->branch_id)->delete();
                    // insert into logs
                    $action = "Edited a purchase that was recorded on ".$purchase->created_at;
                    app('App\Http\Controllers\FunctionController')->log($action, Log::Stock);
                }
            }
            //End
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Delete a purchase
        $purchase = Purchase::where('id',$id)->where('branch_id',Auth::user()->branch_id)->first();
        $type = $purchase->category;
        $purchasedet = PurchaseDetail::where('purchase_id',$id)->where('branch_id',Auth::user()->branch_id)->get();
        if($type == '0'){
            $status = 0;
            foreach($purchasedet as $item) {
                $product = $item->product_id;
                $unitqty = ProductUnit::find($item->unit)->base_quantity;

                $qty = $item->quantity*$unitqty;

                $qtyinstk = Stock::where('product_id',$product)->where('branch_id', Auth::user()->branch_id)->first()->quantity;
                if($qtyinstk<$qty){
                    $status++;
                }
            }
            $settings = GeneralSetting::where('branch_id',Auth::user()->branch_id)->first('allow_negative_stock');
            if($status==0 || $settings->allow_negative_stock == 1){

                // Delete stock movement
                StockMovement::where("transaction_id",$id)->where('type',3)->where('branch_id',Auth::user()->branch_id)->delete();
                // Delete Purchase
                Purchase::where('id',$id)->where('branch_id',Auth::user()->branch_id)->delete();
                $payments= PurchasePayment::where('purchase_id',$id)->where('branch_id',Auth::user()->branch_id)->get();
                foreach($payments as $payment){
                    $pid = $payment->id;
                    // Delete transaction
                    Transaction::where("tId",$pid)->where('category',3)->where('branch_id',Auth::user()->branch_id)->delete();
                    SupplierAccount::where('tId',$pid)->where('category',1)->delete();
                    $payment->delete();
                }
                $purchases = PurchaseDetail::where('purchase_id',$id)->where('branch_id',Auth::user()->branch_id)->get();
                foreach($purchases as $detail){
                    $batchx = $detail->batch;
                    $product = $detail->product_id;
                    $unitqty = ProductUnit::find($detail->unit)->base_quantity;
                    $qty = $detail->quantity*$unitqty;

                    // update batch
                    if($batchx!=''){
                        $batchdet = Batch::where('batch_number',$batchx)->where('product_id',$product)->where('branch_id',Auth::user()->branch_id)
                            ->where('type',0)->first();
                        $batchdet->quantity_out = $batchdet->quantity_out+$qty;
                        $batchdet->save();
                    }
                    // Update product quantity
                    app('App\Http\Controllers\FunctionController')->updateStockLevel($product,-($qty));
                }
                PurchaseDetail::where('purchase_id',$id)->where('branch_id',Auth::user()->branch_id)->delete();
                // insert into logs
                $action = "Deleted a purchase that was recorded on ".$purchase->created_at;
                app('App\Http\Controllers\FunctionController')->log($action, Log::Stock);
            }else{
                return response(['message'=>"The Quantity Purchased for $status products is less than the quantity in stock"],201);
            }
        }
        else{
            $status = 0;
            foreach($purchasedet as $item) {
                $product = $item->product_id;
                $unitqty = ProductUnit::find($item->unit)->base_quantity;
                $qty = $item->quantity*$unitqty;
                $qtyinstr = Store::where('product_id',$product)->where('branch_id', Auth::user()->branch_id)->first()->quantity;
                if($qtyinstr<$qty){
                    $status++;
                }
            }
            if($status==0){
                // Delete stock movement
                StoreMovement::where("transaction_id",$id)->where('type',0)->where('branch_id',Auth::user()->branch_id)->delete();
                // Delete Purchase
                Purchase::where('id',$id)->where('branch_id',Auth::user()->branch_id)->delete();
                $payments=PurchasePayment::where('purchase_id',$id)->where('branch_id',Auth::user()->branch_id)->delete();
                foreach($payments as $payment){
                    $pid = $payment->id;
                    // Delete transaction
                    Transaction::where("tId",$pid)->where('category',3)->where('branch_id',Auth::user()->branch_id)->delete();
                    SupplierAccount::where('tId',$pid)->where('category',1)->delete();
                    $payment->delete();
                }

                $purchases = PurchaseDetail::where('purchase_id',$id)->where('branch_id',Auth::user()->branch_id)->get();
                foreach($purchases as $detail){
                    $batchx = $detail->batch;
                    $product = $detail->product_id;
                    $unitqty = ProductUnit::find($detail->unit)->base_quantity;
                    $qty = $detail->quantity*$unitqty;

                    // update batch
                    if($batchx!=''){
                        $batchdet = Batch::where('batch_number',$batchx)->where('product_id',$product)->where('branch_id',Auth::user()->branch_id)
                            ->where('type',1)->first();
                        $batchdet->quantity_out = $batchdet->quantity_out+$qty;
                        $batchdet->save();
                    }
                    // Update product quantity
                    app('App\Http\Controllers\FunctionController')->updateStoreLevel($product,-($qty));
                }
                PurchaseDetail::where('purchase_id',$id)->where('branch_id',Auth::user()->branch_id)->delete();
                // insert into logs
                $action = "Deleted a store purchase that was recorded on ".$purchase->created_at;
                app('App\Http\Controllers\FunctionController')->log($action, Log::Stock);
            }else{
                return response(['message'=>"The Quantity Purchased for $status products is less than the quantity in store"],201);
            }
        }
    }


    // Updating amount paid and mode
    public function updateAmtPaid(Request $request, $id){
        $request->validate([
            'paid' => 'required',
            'mode' => 'required',
            'id' => 'required',
        ]);
        $payments = PurchasePayment::where('branch_id',Auth::user()->branch_id)->where('purchase_id',$id)->count();
        $paymentAmt = PurchasePayment::where('branch_id',Auth::user()->branch_id)->where('purchase_id',$id)->sum('amount');
        if($payments > 1){
            return response(['message'=>"This Purchase has more than one payment. Delete it instead"],201);
        }else if($paymentAmt == 0){
            return response(['message'=>"This Purchase has no payment to be changed. Please record a payment in debtors or Delete it instead"],201);

        }
        else{
            $purchase = Purchase::find($id);
            if(str_replace(',', '', $request->paid)>($purchase->total - $purchase->discount)){
                return response(['message'=>"Amount Paid Cannot be greater than Purchase Total"],201);

                }
            $purchase->paid = str_replace(',', '', $request->paid);
            $purchase->save();
            // Update Transaction
            $ppaymentId = PurchasePayment::where('purchase_id',$id)->where('branch_id',Auth::user()->branch_id)->first()->id;
            Transaction::where('category',3)->where('tId',$ppaymentId)->where('branch_id',Auth::user()->branch_id)
            ->update(['amount_out'=>str_replace(',', '', $request->paid),'mode'=>$request->mode]);
            // Update payment
            PurchasePayment::where('branch_id',Auth::user()->branch_id)->where('purchase_id',$id)
            ->update(['amount'=>str_replace(',', '', $request->paid),'mode'=>$request->mode]);
            SupplierAccount::where("tId",$ppaymentId)->where('category',1)
            ->update(['mode'=>$request->mode,'amount_out' => str_replace(',', '',$request->paid)]);


        }
    }

    // All creditors
    public function creditors(){
        $creditors = Purchase::whereRaw('total >paid')->where('branch_id', Auth::user()->branch_id)->orderBy("created_at", "desc")->get();
        return PurchaseResource::collection($creditors);
    }

    // Imported creditors
    public function imported_creditors(){
        $imported_creditors = Creditor::whereRaw('total >paid')->where('branch_id', Auth::user()->branch_id)->orderBy("created_at", "desc")->get();
        return CreditorResource::collection($imported_creditors);
    }

    public function clearCredit(Request $request, $id){
        $request->validate([
            'paid' => 'required',
            'mode' => 'required',
        ]);
        $purchase = Purchase::find($id);
        $purchase->paid = $purchase->paid + str_replace(",","",$request->paid);
        $purchase->save();

        // record payment
        $thismon = date("m");
        if(strlen($thismon)<2){
            $thismon = "0".$thismon;
        }
        $checklast = PurchasePayment::where('branch_id', Auth::user()->branch_id)->where('month',$thismon)
        ->where('year',date("Y"))->first();
        if($checklast){
            $lrec = $checklast->recno;
        }
        else{
            $lrec = 0;
        }
        $nrec = $lrec+1;
        $receipt = time().$nrec;
        $ppayment = PurchasePayment::create([
            'purchase_id' => $purchase->id,
            'amount' => str_replace(",","",$request->paid),
            'mode' => $request->mode,
            'receipt' => $receipt,
            'date' => $request->date ?? date('Y-m-d'),
            'recno' => $nrec,
            'month' => date("m"),
            'year' => date("Y"),
            'user_id' => Auth::user()->id,
            'branch_id' => Auth::user()->branch_id,
            'business_id' => Auth::user()->business_id,
        ]);

        SupplierAccount::create([
            'supplier_id' => $purchase->supplier_id,
            'amount_out' => str_replace(",","",$request->paid),
            'amount_in' => 0,
            'tId' => $ppayment->id,
            'mode' => $ppayment->mode,
            'date' => $ppayment->date,
            'description' => "Clearing balance from buying items of ".$purchase->total,
            'user_id' => $ppayment->user_id,
            'branch_id' => $ppayment->branch_id,
            'business_id' => $ppayment->business_id,
        ]);

        // insert into transactions
        app('App\Http\Controllers\FunctionController')->transaction(3,$ppayment->id,0,str_replace(",","",$request->paid),$request->mode,"Credit Clearance",$ppayment->date);
        // insert into logs
        app('App\Http\Controllers\FunctionController')->log("Cleared a credit of amount $request->paid", Log::Stock);
    }

    public function clearImportedCredit(Request $request,$id){
        $request->validate([
            'paid' => 'required',
            'mode' => 'required',
        ]);
        $creditor = Creditor::find($id);
        $creditor->paid = $creditor->paid + str_replace(",","",$request->paid);
        $creditor->save();

        // record payment
        $thismon = date("m");
        if(strlen($thismon)<2){
            $thismon = "0".$thismon;
        }
        $checklast = PurchasePayment::where('branch_id', Auth::user()->branch_id)->where('month',$thismon)
        ->where('year',date("Y"))->first();
        if($checklast){
            $lrec = $checklast->recno;
        }
        else{
            $lrec = 0;
        }
        $nrec = $lrec+1;
        $receipt = time().$nrec;
        $ppayment = PurchasePayment::create([
            'purchase_id' => $creditor->id,
            'amount' => str_replace(",","",$request->paid),
            'mode' => $request->mode,
            'receipt' => $receipt,
            'date' => $request->date ?? date('Y-m-d'),
            'recno' => $nrec,
            'month' => date("m"),
            'year' => date("Y"),
            'status' => 2,
            'user_id' => Auth::user()->id,
            'branch_id' => Auth::user()->branch_id,
            'business_id' => Auth::user()->business_id,
        ]);


        // insert into transactions
        app('App\Http\Controllers\FunctionController')->transaction(3,$ppayment->id,0,str_replace(",","",$request->paid),$request->mode,"Previous Credit Clearance",$ppayment->date);
        // insert into logs
        app('App\Http\Controllers\FunctionController')->log("Cleared a previous credit of amount $request->paid", Log::Stock);
    }
}
