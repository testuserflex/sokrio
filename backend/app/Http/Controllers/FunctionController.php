<?php

namespace App\Http\Controllers;

use App\Models\CustomerAccount;
use App\Models\Invoice;
use App\Models\SupplierAccount;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;
use App\Models\Product;
use App\Models\PriceChange;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\Store;
use App\Models\Transaction;
use App\Models\Notification;

class FunctionController extends Controller
{
    public function log( string $action,$type){
        Log::create([
            'activity' => $action,
            'category' => $type,
            'user_id' => Auth::user()->id,
            'branch_id' => Auth::user()->branch_id,
            'business_id' => Auth::user()->business_id,
        ]);
    }

    public function updateStockLevel($product,$qty){
        // Update product qty
        $prod = Product::where('id',$product)->first();
        $prod->quantity = $prod->quantity+$qty;
        $prod->save();
        // Update stock qty
        $stk = Stock::where('product_id',$product)->first();
        $stk->quantity = $stk->quantity+$qty;
        $stk->save();

    }

    public function updateStoreLevel($product,$qty){
        // Update stock qty
        $stk = Store::where('product_id',$product)->where('branch_id', Auth::user()->branch_id)->first();
        if(!$stk){
            $price = Stock::where('product_id',$product)->where('branch_id', Auth::user()->branch_id)->first()->buying_price;
            Store::create([
                'product_id' =>$product,
                'quantity' => $qty ?? 0,
                'buying_price' =>$price,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
                'user_id' => Auth::user()->id,
            ]);
        }else{
            $stk->quantity = $stk->quantity+$qty;
            $stk->save();
        }
    }

    public function stockmove($product,$type,$qtyin,$qtyout,$txtId) {
        if($qtyin>0){
            $oldqty = Product::find($product)->quantity - $qtyin;
        }elseif($qtyout>0){
            $oldqty = Product::find($product)->quantity + $qtyout;
        }
        StockMovement::create([
            'product_id' =>$product,
            'from_quantity' => $oldqty,
            'quantity_in' => str_replace(",","",$qtyin),
            'quantity_out' => str_replace(",","",$qtyout),
            'date' => date('Y-m-d'),
            'type' => $type,
            'transaction_id' => $txtId,
            'branch_id' => Auth::user()->branch_id,
            'business_id' => Auth::user()->business_id,
            'user_id' => Auth::user()->id,
        ]);
    }

    public function transaction($cat,$tid,$amountin,$amount_out,$mode,$desc,$date = null){
        Transaction::create([
            'category' => $cat,
            'amount_in' => $amountin,
            'amount_out' => $amount_out,
            'mode' => $mode,
            'tId' => $tid,
            'date' => $date??date('Y-m-d'),
            'description' => $desc,
            'user_id' => Auth::user()->id,
            'branch_id' => Auth::user()->branch_id,
            'business_id' => Auth::user()->business_id
        ]);
    }
    public function getAverageStockBPrice($product,$qty,$unitprice){
        $stock=Stock::where('product_id',$product)->where('branch_id',Auth::user()->branch_id)->first();
        $stockqty = $stock->quantity;
        $stockbp = $stock->average_buyingprice;
        $newprice = $qty?round((($stockqty*$stockbp) + ($qty*$unitprice))/($stockqty+$qty)):$stockbp;
        $stock->average_buyingprice=$newprice;
        $stock->buying_price=$unitprice;
        $stock->save();

        if($stockbp != $newprice){
            $pricechange = PriceChange::create([
                'product_id' => $product,
                'old_price' => $stockbp,
                'new_price' => $unitprice,
                'type' => 0,
                'user_id' => Auth::user()->id,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
            ]);
        }
    }
    public function getAverageStoreBPrice($product,$qty,$unitprice){
        $stock=Store::where('product_id',$product)->where('branch_id',Auth::user()->branch_id)->first();
        $stockqty = $stock->quantity;
        $stockbp = $stock->average_buyingprice;
        $newprice = $qty?round((($stockqty*$stockbp) + ($qty*$unitprice))/($stockqty+$qty)):$stockbp;
        $stock->average_buyingprice=$newprice;
        $stock->buying_price=$unitprice;
        $stock->save();

        if($stockbp != $newprice){
            $pricechange = PriceChange::create([
                'product_id' => $product,
                'old_price' => $stockbp,
                'new_price' => $unitprice,
                'type' => 1,
                'user_id' => Auth::user()->id,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
            ]);
        }
    }
    public function generateReceipt(){
        $year=date('Y');
        $recyear=date('y');
        $recmonth=date('m');
        $month=(int) $recmonth;
        $branch=Auth::user()->branch_id;
        $maxrec=Payment::where('branch_id',$branch)
            ->where('year',$year)->where('month',$month)->max('recno');
//        if ($maxrec->count() > 0)
        $rec = (int) $maxrec;
        $recno=$rec+1;
        if (strlen($recno)>3) {
            $receipt_number=$branch.$recyear.$recmonth.$recno;
        }elseif (strlen($recno)>2) {
            $receipt_number=$branch.$recyear.$recmonth."0".$recno;
            # code...
        }elseif (strlen($recno)>1) {
            $receipt_number=$branch.$recyear.$recmonth."00".$recno;
            # code...
        }else{
            $receipt_number=$branch.$recyear.$recmonth."000".$recno;

        }
        $arr = [
            'recno' => $recno,
            'receipt' => $receipt_number
        ];
        return $arr;
    }

public function generateInvoice(){
        $year=date('Y');
        $recyear=date('y');
        $recmonth=date('m');
        $month=(int) $recmonth;
        $branch=Auth::user()->branch_id;
        $maxrec=Invoice::where('branch_id',$branch)
            ->where('year',$year)->where('month',$month)->max('recno');
//        if ($maxrec->count() > 0)
        $rec = (int) $maxrec;
        $recno=$rec+1;
        if (strlen($recno)>3) {
            $invoice_number=$branch.$recyear.$recmonth.$recno;
        }elseif (strlen($recno)>2) {
            $invoice_number=$branch.$recyear.$recmonth."0".$recno;
            # code...
        }elseif (strlen($recno)>1) {
            $invoice_number=$branch.$recyear.$recmonth."00".$recno;
            # code...
        }else{
            $invoice_number=$branch.$recyear.$recmonth."000".$recno;

        }
        $arr = [
            'recno' => $recno,
            'invoice' => $invoice_number
        ];
        return $arr;
    }

    public function customerBalance($customer){
        $amountin=CustomerAccount::where('customer_id',$customer)->sum('amount_in');
        $amountout=CustomerAccount::where('customer_id',$customer)->sum('amount_out');
        return ($amountin-$amountout) ?? 0;
    }
    public function supplierBalance($supplier){
        $amountin=SupplierAccount::where('supplier_id',$supplier)->sum('amount_in');
        $amountout=SupplierAccount::where('supplier_id',$supplier)->sum('amount_out');
        return ($amountin-$amountout) ?? 0;
    }

}
