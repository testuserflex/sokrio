<?php

namespace App\Http\Controllers;

use App\Http\Controllers\FunctionController;
use App\Http\Resources\SaleDetailResource;
use App\Http\Resources\SaleResource;
use App\Http\Resources\SaleReturnResource;
use App\Http\Resources\SaleCartResource;
use App\Http\Resources\PaymentResource;
use App\Http\Resources\DebtorResource;
use App\Models\Batch;
use App\Models\CustomerAccount;
use App\Models\Log;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Sale;
use App\Models\SaleCart;
use App\Models\Customer;
use App\Models\Messaging;
use App\Models\Business;
use App\Models\SmsBalance;
use App\Models\SaleDetail;
use App\Models\SaleReturn;
use App\Models\PaymentOption;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\ProductCategory;
use App\Models\Transaction;
use App\Models\Debtor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\GeneralSetting;
use App\Jobs\AppreciationSmsJob;
use Carbon\Carbon;
use App\Models\CustomerDeposit;

class SaleController extends Controller
{
    public  $functionmaster;
    public function __construct(){
      $this->functionmaster  = new FunctionController();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $days = Carbon::now()->subDays(3)->format('Y-m-d');
        if(Auth::user()->branch_id==0){
            $sales = Sale::where('business_id', Auth::user()->business_id)->orderBy("created_at", "desc")->where('sale_date','=', date('Y-m-d'))->where('status', 1)->get();
        }
        else{
            $sales = Sale::where('branch_id', Auth::user()->branch_id)->orderBy("created_at", "desc")->where('sale_date','=', date('Y-m-d'))->where('status', 1)->get();
        }
        return SaleResource::collection($sales);
    }

    // Fetch Client Payments
    public function credit_payments(Request $request){
        if(Auth::user()->branch_id==0){
            if($request->customer != ""){
                $crpayments = Payment::whereColumn('date', '!=', 'sales.sale_date')
                ->join('sales', 'payments.sale_id', '=', 'sales.id')
                ->whereBetween('payments.date', [$request->datefrom, $request->dateto])
                // ->where('payments.branch_id', Auth::user()->branch_id)
                ->where("sales.customer_id", $request->customer)
                ->with('sale')
                ->orderBy("payments.created_at", "desc")
                ->get();
            }else{
                $crpayments = Payment::whereColumn('date', '!=', 'sales.sale_date')
                ->join('sales', 'payments.sale_id', '=', 'sales.id')
                ->whereBetween('payments.date', [$request->datefrom, $request->dateto])
                ->where('payments.business_id', Auth::user()->business_id)
                ->with('sale')
                ->orderBy("payments.created_at", "desc")
                ->get();
            }
        }
        else{
            if($request->customer != ""){
                $crpayments = Payment::whereColumn('date', '!=', 'sales.sale_date')
                ->join('sales', 'payments.sale_id', '=', 'sales.id')
                ->whereBetween('payments.date', [$request->datefrom, $request->dateto])
                // ->where('payments.branch_id', Auth::user()->branch_id)
                ->where("sales.customer_id", $request->customer)
                ->with('sale')
                ->orderBy("payments.created_at", "desc")
                ->get();
            }else{
                $crpayments = Payment::whereColumn('date', '!=', 'sales.sale_date')
                ->join('sales', 'payments.sale_id', '=', 'sales.id')
                ->whereBetween('payments.date', [$request->datefrom, $request->dateto])
                ->where('payments.branch_id', Auth::user()->branch_id)
                ->with('sale')
                ->orderBy("payments.created_at", "desc")
                ->get();
            }
        }
        return PaymentResource::collection($crpayments);
    }

    public function sales_data(Request $request)
    {
        if(Auth::user()->branch_id==0){
            if($request->branch_id == 0){
                if($request->customer == '' && $request->receipt == ''){
                    $sales = Sale::where('business_id', Auth::user()->business_id)->whereBetween("sale_date", [$request->datefrom, $request->dateto])->orderBy("created_at", "desc")->get();
                }elseif($request->customer != '' && $request->receipt == ''){
                    $sales = Sale::where('business_id', Auth::user()->business_id)->where('branch_id', $request->branch_id)->whereBetween("sale_date", [$request->datefrom, $request->dateto])->where('customer_id',$request->customer)->orderBy("created_at", "desc")->get();
                }elseif($request->customer == '' && $request->receipt != ''){
                    $sales = Sale::where('business_id', Auth::user()->business_id)->where('receipt',$request->receipt)->where('branch_id', $request->branch_id)->whereBetween("sale_date", [$request->datefrom, $request->dateto])
                    ->orWhere('business_id', Auth::user()->business_id)->where('offline_receipt',$request->receipt)->where('branch_id', $request->branch_id)->whereBetween("sale_date", [$request->datefrom, $request->dateto])->orderBy("created_at", "desc")->get();
                }elseif($request->customer != '' && $request->receipt != ''){
                    $sales = Sale::where('business_id', Auth::user()->business_id)->where('receipt',$request->receipt)->where('branch_id', $request->branch_id)->where('customer_id',$request->customer)->whereBetween("sale_date", [$request->datefrom, $request->dateto])
                    ->orWhere('business_id', Auth::user()->business_id)->where('offline_receipt',$request->receipt)->where('branch_id', $request->branch_id)->where('customer_id',$request->customer)->whereBetween("sale_date", [$request->datefrom, $request->dateto])->orderBy("created_at", "desc")->get();
                }
            }else{
                if($request->customer == '' && $request->receipt == ''){
                    $sales = Sale::where('branch_id', $request->branch_id)->whereBetween("sale_date", [$request->datefrom, $request->dateto])->orderBy("created_at", "desc")->get();
                }elseif($request->customer != '' && $request->receipt == ''){
                    $sales = Sale::where('branch_id', $request->branch_id)->whereBetween("sale_date", [$request->datefrom, $request->dateto])->where('customer_id',$request->customer)->orderBy("created_at", "desc")->get();
                }elseif($request->customer == '' && $request->receipt != ''){
                    $sales = Sale::where('branch_id', $request->branch_id)->where('receipt',$request->receipt)->whereBetween("sale_date", [$request->datefrom, $request->dateto])
                    ->orWhere('branch_id', $request->branch_id)->where('offline_receipt',$request->receipt)->whereBetween("sale_date", [$request->datefrom, $request->dateto])->orderBy("created_at", "desc")->get();
                }elseif($request->customer != '' && $request->receipt != ''){
                    $sales = Sale::where('branch_id', $request->branch_id)->where('receipt',$request->receipt)->where('customer_id',$request->customer)->whereBetween("sale_date", [$request->datefrom, $request->dateto])
                    ->orWhere('branch_id', $request->branch_id)->where('offline_receipt',$request->receipt)->where('customer_id',$request->customer)->whereBetween("sale_date", [$request->datefrom, $request->dateto])->orderBy("created_at", "desc")->get();
                }
            }
        }else{
            if($request->customer == '' && $request->receipt == ''){
                $sales = Sale::where('branch_id', Auth::user()->branch_id)->whereBetween("sale_date", [$request->datefrom, $request->dateto])->orderBy("created_at", "desc")->get();
            }elseif($request->customer != '' && $request->receipt == ''){
                $sales = Sale::where('branch_id', Auth::user()->branch_id)->whereBetween("sale_date", [$request->datefrom, $request->dateto])->where('customer_id',$request->customer)->orderBy("created_at", "desc")->get();
            }elseif($request->customer == '' && $request->receipt != ''){
                $sales = Sale::where('branch_id', Auth::user()->branch_id)->where('receipt',$request->receipt)->whereBetween("sale_date", [$request->datefrom, $request->dateto])
                ->orWhere('branch_id', Auth::user()->branch_id)->where('offline_receipt',$request->receipt)->whereBetween("sale_date", [$request->datefrom, $request->dateto])->orderBy("created_at", "desc")->get();
            }elseif($request->customer != '' && $request->receipt != ''){
                $sales = Sale::where('branch_id', Auth::user()->branch_id)->where('receipt',$request->receipt)->where('customer_id',$request->customer)->whereBetween("sale_date", [$request->datefrom, $request->dateto])
                ->orWhere('branch_id', Auth::user()->branch_id)->where('offline_receipt',$request->receipt)->where('customer_id',$request->customer)->whereBetween("sale_date", [$request->datefrom, $request->dateto])->orderBy("created_at", "desc")->get();
            }
        }
        return SaleResource::collection($sales);
    }

    public function fetch_salereturns()
    {
        if(Auth::user()->branch_id==0){
            $salereturn = SaleReturn::where('business_id', Auth::user()->business_id)->orderBy("created_at", "desc")->get();
        }
        else{
            $salereturn = SaleReturn::where('branch_id', Auth::user()->branch_id)->orderBy("created_at", "desc")->get();
        }
        return SaleReturnResource::collection($salereturn);
    }

    public function fetchsales_details(Request $request)
    {
        if(Auth::user()->branch_id==0){
            if($request->period == 12){
                $saledetail = SaleDetail::where('business_id', Auth::user()->business_id)->whereHas('sale', function ($query) use($request) {
                    return $query ->whereMonth('sale_date', $request->saledata);
                })->orderBy("created_at", "desc")->get();
            }else{
                $saledetail = SaleDetail::where('business_id', Auth::user()->business_id)->whereHas('sale', function ($query) use($request) {
                    return $query ->where('sale_date', $request->saledata);
                })->orderBy("created_at", "desc")->get();
            }
        }
        else{
            if($request->period == 12){
                $saledetail = SaleDetail::where('branch_id', Auth::user()->branch_id)->whereHas('sale', function ($query) use($request) {
                    return $query ->whereMonth('sale_date', $request->saledata);
                })->orderBy("created_at", "desc")->get();
            }else{
                $saledetail = SaleDetail::where('branch_id', Auth::user()->branch_id)->whereHas('sale', function ($query) use($request) {
                    return $query ->where('sale_date', $request->saledata);
                })->orderBy("created_at", "desc")->get();
            }
        }
        return SaleDetailResource::collection($saledetail);
    }

    // Receipt Printing
    public function receipt_data($id)
    {
        $items = Sale::find($id);
        return new SaleResource($items);
    }

    // Confirm Ready Order
    public function KOT()
    {
        if(Auth::user()->branch_id==0){
            $KOT = SaleCart::where('business_id',Auth::user()->business_id)->where('hold',1)->where('cart_status',1)->orderBy("created_at", "asc")->get();
        }
        else{
            $KOT = SaleCart::where('branch_id',Auth::user()->branch_id)->where('hold',1)->where('cart_status',1)->orderBy("created_at", "asc")->get();
        }
        return SaleCartResource::collection($KOT);
    }

    public function sales_report(Request $request)
    {
        if(Auth::user()->branch_id==0){
            if($request->branch_id == 0){
                if($request->product == '' && $request->category == ''){
                    $sales = SaleDetail::where('business_id', Auth::user()->business_id)->orderBy("created_at", "desc")
                    ->whereHas('sale', function ($query) use($request) {
                        return $query ->whereBetween("sale_date", [$request->datefrom, $request->dateto]);
                    })->with('sale')->get();
                }elseif($request->product != '' && $request->category == ''){
                    $sales = SaleDetail::where('business_id', Auth::user()->business_id)->orderBy("created_at", "desc")
                    ->whereHas('sale', function ($query) use($request) {
                        return $query ->whereBetween("sale_date", [$request->datefrom, $request->dateto]);
                    })->whereHas('product', function ($query) use($request) {
                        return $query->where('id', $request->product);
                    })->with('product','sale')->get();
                }elseif($request->product == '' && $request->category != ''){
                    $sales = SaleDetail::where('business_id', Auth::user()->business_id)->orderBy("created_at", "desc")
                    ->whereHas('sale', function ($query) use($request) {
                        return $query ->whereBetween("sale_date", [$request->datefrom, $request->dateto]);
                    })->whereHas('product', function ($query) use($request) {
                        return $query->whereRelation('businessproduct', 'category_id', $request->category);
                    })->with('product','sale')->get();
                }else{
                    $sales = SaleDetail::where('business_id', Auth::user()->business_id)->orderBy("created_at", "desc")
                    ->whereHas('sale', function ($query) use($request) {
                        return $query ->whereBetween("sale_date", [$request->datefrom, $request->dateto]);
                    })->whereHas('product', function ($query) use($request) {
                        return $query->where('id', $request->product)->whereRelation('businessproduct', 'category_id', $request->category);
                    })->with('product','sale')->get();
                }
            }else{
                if($request->product == '' && $request->category == ''){
                    $sales = SaleDetail::where('branch_id', $request->branch_id)->orderBy("created_at", "desc")
                    ->whereHas('sale', function ($query) use($request) {
                        return $query ->whereBetween("sale_date", [$request->datefrom, $request->dateto]);
                    })->with('sale')->get();
                }elseif($request->product != '' && $request->category == ''){
                    $sales = SaleDetail::where('branch_id', $request->branch_id)->orderBy("created_at", "desc")
                    ->whereHas('sale', function ($query) use($request) {
                        return $query ->whereBetween("sale_date", [$request->datefrom, $request->dateto]);
                    })->whereHas('product', function ($query) use($request) {
                        return $query->where('id', $request->product);
                    })->with('product','sale')->get();
                }elseif($request->product == '' && $request->category != ''){
                    $sales = SaleDetail::where('branch_id', $request->branch_id)->orderBy("created_at", "desc")
                    ->whereHas('sale', function ($query) use($request) {
                        return $query ->whereBetween("sale_date", [$request->datefrom, $request->dateto]);
                    })->whereHas('product', function ($query) use($request) {
                        return $query->whereRelation('businessproduct', 'category_id', $request->category);
                    })->with('product','sale')->get();
                }else{
                    $sales = SaleDetail::where('branch_id', $request->branch_id)->orderBy("created_at", "desc")
                    ->whereHas('sale', function ($query) use($request) {
                        return $query ->whereBetween("sale_date", [$request->datefrom, $request->dateto]);
                    })->whereHas('product', function ($query) use($request) {
                        return $query->where('id', $request->product)->whereRelation('businessproduct', 'category_id', $request->category);
                    })->with('product','sale')->get();
                }
            }
        }
        else{
            if($request->product == '' && $request->category == ''){
                $sales = SaleDetail::where('branch_id', Auth::user()->branch_id)->orderBy("created_at", "desc")
                ->whereHas('sale', function ($query) use($request) {
                    return $query ->whereBetween("sale_date", [$request->datefrom, $request->dateto]);
                })->with('sale')->get();
            }elseif($request->product != '' && $request->category == ''){
                $sales = SaleDetail::where('branch_id', Auth::user()->branch_id)->orderBy("created_at", "desc")
                ->whereHas('sale', function ($query) use($request) {
                    return $query ->whereBetween("sale_date", [$request->datefrom, $request->dateto]);
                })->whereHas('product', function ($query) use($request) {
                    return $query->where('id', $request->product);
                })->with('product','sale')->get();
            }elseif($request->product == '' && $request->category != ''){
                $sales = SaleDetail::where('branch_id', Auth::user()->branch_id)->orderBy("created_at", "desc")
                ->whereHas('sale', function ($query) use($request) {
                    return $query ->whereBetween("sale_date", [$request->datefrom, $request->dateto]);
                })->whereHas('product', function ($query) use($request) {
                    return $query->whereRelation('businessproduct', 'category_id', $request->category);
                })->with('product','sale')->get();
            }else{
                $sales = SaleDetail::where('branch_id', Auth::user()->branch_id)->orderBy("created_at", "desc")
                ->whereHas('sale', function ($query) use($request) {
                    return $query ->whereBetween("sale_date", [$request->datefrom, $request->dateto]);
                })->whereHas('product', function ($query) use($request) {
                    return $query->where('id', $request->product)->whereRelation('businessproduct', 'category_id', $request->category);
                })->with('product','sale')->get();
            }
        }
        return SaleDetailResource::collection($sales);
    }


    // Sales analysis by Category, Product, User and Dates
    public function sales_analysis(Request $request)
    {
        if(Auth::user()->branch_id==0){
            if($request->branch_id == 0){
                if($request->user != '' && $request->category == ''){
                    $sales = SaleDetail::where('business_id', Auth::user()->business_id)->orderBy("created_at", "desc")
                    ->whereHas('sale', function ($query) use($request) {
                        return $query ->whereBetween("sale_date", [$request->datefrom, $request->dateto]);
                    })->whereHas('user', function ($query) use($request) {
                        return $query->where('id', $request->user);
                    })->with('user','sale','product')->get();
                }elseif($request->user == '' && $request->category != ''){
                    $sales = SaleDetail::where('business_id', Auth::user()->business_id)->orderBy("created_at", "desc")
                    ->whereHas('sale', function ($query) use($request) {
                        return $query ->whereBetween("sale_date", [$request->datefrom, $request->dateto]);
                    })->whereHas('product', function ($query) use($request) {
                        return $query->whereRelation('businessproduct', 'category_id', $request->category);
                    })->with('user','sale','product')->get();
                }elseif($request->user != '' && $request->category != ''){
                    $sales = SaleDetail::where('business_id', Auth::user()->business_id)->orderBy("created_at", "desc")
                    ->whereHas('sale', function ($query) use($request) {
                        return $query ->whereBetween("sale_date", [$request->datefrom, $request->dateto]);
                    })->whereHas('product', function ($query) use($request) {
                        return $query->whereRelation('businessproduct', 'category_id', $request->category);
                    })->whereHas('user', function ($query) use($request) {
                        return $query->where('id', $request->user);
                    })->with('user','sale','product')->get();
                }else{
                    $sales = SaleDetail::where('business_id', Auth::user()->business_id)->orderBy("created_at", "desc")
                    ->whereHas('sale', function ($query) use($request) {
                        return $query ->whereBetween("sale_date", [$request->datefrom, $request->dateto]);
                    })->with('sale')->get();
                }
            }else{
                if($request->user != '' && $request->category == ''){
                    $sales = SaleDetail::where('branch_id', $request->branch_id)->orderBy("created_at", "desc")
                    ->whereHas('sale', function ($query) use($request) {
                        return $query ->whereBetween("sale_date", [$request->datefrom, $request->dateto]);
                    })->whereHas('user', function ($query) use($request) {
                        return $query->where('id', $request->user);
                    })->with('user','sale','product')->get();
                }elseif($request->user == '' && $request->category != ''){
                    $sales = SaleDetail::where('branch_id', $request->branch_id)->orderBy("created_at", "desc")
                    ->whereHas('sale', function ($query) use($request) {
                        return $query ->whereBetween("sale_date", [$request->datefrom, $request->dateto]);
                    })->whereHas('product', function ($query) use($request) {
                        return $query->whereRelation('businessproduct', 'category_id', $request->category);
                    })->with('user','sale','product')->get();
                }elseif($request->user != '' && $request->category != ''){
                    $sales = SaleDetail::where('branch_id', $request->branch_id)->orderBy("created_at", "desc")
                    ->whereHas('sale', function ($query) use($request) {
                        return $query ->whereBetween("sale_date", [$request->datefrom, $request->dateto]);
                    })->whereHas('product', function ($query) use($request) {
                        return $query->whereRelation('businessproduct', 'category_id', $request->category);
                    })->whereHas('user', function ($query) use($request) {
                        return $query->where('id', $request->user);
                    })->with('user','sale','product')->get();
                }else{
                    $sales = SaleDetail::where('branch_id', $request->branch_id)->orderBy("created_at", "desc")
                    ->whereHas('sale', function ($query) use($request) {
                        return $query ->whereBetween("sale_date", [$request->datefrom, $request->dateto]);
                    })->with('sale')->get();
                }
            }
        }
        else{
            if($request->user != '' && $request->category == ''){
                $sales = SaleDetail::where('branch_id', Auth::user()->branch_id)->orderBy("created_at", "desc")
                ->whereHas('sale', function ($query) use($request) {
                    return $query ->whereBetween("sale_date", [$request->datefrom, $request->dateto]);
                })->whereHas('user', function ($query) use($request) {
                    return $query->where('id', $request->user);
                })->with('user','sale','product')->get();
            }elseif($request->user == '' && $request->category != ''){
                $sales = SaleDetail::where('branch_id', Auth::user()->branch_id)->orderBy("created_at", "desc")
                ->whereHas('sale', function ($query) use($request) {
                    return $query ->whereBetween("sale_date", [$request->datefrom, $request->dateto]);
                })->whereHas('product', function ($query) use($request) {
                    return $query->whereRelation('businessproduct', 'category_id', $request->category);
                })->with('user','sale','product')->get();
            }elseif($request->user != '' && $request->category != ''){
                $sales = SaleDetail::where('branch_id', Auth::user()->branch_id)->orderBy("created_at", "desc")
                ->whereHas('sale', function ($query) use($request) {
                    return $query ->whereBetween("sale_date", [$request->datefrom, $request->dateto]);
                })->whereHas('product', function ($query) use($request) {
                    return $query->whereRelation('businessproduct', 'category_id', $request->category);
                })->whereHas('user', function ($query) use($request) {
                    return $query->where('id', $request->user);
                })->with('user','sale','product')->get();
            }else{
                $sales = SaleDetail::where('branch_id', Auth::user()->branch_id)->orderBy("created_at", "desc")
                ->whereHas('sale', function ($query) use($request) {
                    return $query ->whereBetween("sale_date", [$request->datefrom, $request->dateto]);
                })->with('sale')->get();
            }
        }
        return SaleDetailResource::collection($sales);
    }

    // Sales analysis by Category, Product, User and Dates
    public function monthlysales_analysis(Request $request)
    {
        if(Auth::user()->branch_id==0){
            if($request->branch_id == 0){
                if($request->month == 12){
                $jansales = Sale::where('business_id', Auth::user()->business_id)->whereMonth('sale_date', '1')->whereYear('sale_date',$request->year)->sum('total');
                $febsales = Sale::where('business_id', Auth::user()->business_id)->whereMonth('sale_date', '2')->whereYear('sale_date',$request->year)->sum('total');
                $marsales = Sale::where('business_id', Auth::user()->business_id)->whereMonth('sale_date', '3')->whereYear('sale_date',$request->year)->sum('total');
                $aprsales = Sale::where('business_id', Auth::user()->business_id)->whereMonth('sale_date', '4')->whereYear('sale_date',$request->year)->sum('total');
                $maysales = Sale::where('business_id', Auth::user()->business_id)->whereMonth('sale_date', '5')->whereYear('sale_date',$request->year)->sum('total');
                $junsales = Sale::where('business_id', Auth::user()->business_id)->whereMonth('sale_date', '6')->whereYear('sale_date',$request->year)->sum('total');
                $julsales = Sale::where('business_id', Auth::user()->business_id)->whereMonth('sale_date', '7')->whereYear('sale_date',$request->year)->sum('total');
                $augsales = Sale::where('business_id', Auth::user()->business_id)->whereMonth('sale_date', '8')->whereYear('sale_date',$request->year)->sum('total');
                $sepsales = Sale::where('business_id', Auth::user()->business_id)->whereMonth('sale_date', '9')->whereYear('sale_date',$request->year)->sum('total');
                $octsales = Sale::where('business_id', Auth::user()->business_id)->whereMonth('sale_date', '10')->whereYear('sale_date',$request->year)->sum('total');
                $novsales = Sale::where('business_id', Auth::user()->business_id)->whereMonth('sale_date', '11')->whereYear('sale_date',$request->year)->sum('total');
                $decsales = Sale::where('business_id', Auth::user()->business_id)->whereMonth('sale_date', '12')->whereYear('sale_date',$request->year)->sum('total');
                $totalsales = Sale::where('business_id', Auth::user()->business_id)->whereYear('sale_date',$request->year)->sum('total');
                $array = [
                    'jansales' => number_format($jansales),
                    'febsales' => number_format($febsales),
                    'marsales' => number_format($marsales),
                    'aprsales' => number_format($aprsales),
                    'maysales' => number_format($maysales),
                    'junsales' => number_format($junsales),
                    'julsales' => number_format($julsales),
                    'augsales' => number_format($augsales),
                    'sepsales' => number_format($sepsales),
                    'octsales' => number_format($octsales),
                    'novsales' => number_format($novsales),
                    'decsales' => number_format($decsales),
                    'totalsales' => number_format($totalsales),
                ];
                return $array;
                }else{
                    $sales = DB::table("sale_details")
                    ->join('sales','sales.id','sale_details.sale_id')
                    ->where('sale_details.business_id', Auth::user()->business_id)
                    ->whereYear('sales.sale_date',$request->year)
                    ->whereMonth('sales.sale_date',$request->month+1)
                    ->select('sales.sale_date as date')
                    ->selectRaw('sum(sale_details.quantity*sale_details.selling_price) as amount')
                    ->groupBy('sales.sale_date')
                    ->orderByRaw('sales.sale_date DESC')
                    ->get();
                }
            }else{
                if($request->month == 12){
                $jansales = Sale::where('branch_id', $request->branch_id)->whereMonth('sale_date', '1')->whereYear('sale_date',$request->year)->sum('total');
                $febsales = Sale::where('branch_id', $request->branch_id)->whereMonth('sale_date', '2')->whereYear('sale_date',$request->year)->sum('total');
                $marsales = Sale::where('branch_id', $request->branch_id)->whereMonth('sale_date', '3')->whereYear('sale_date',$request->year)->sum('total');
                $aprsales = Sale::where('branch_id', $request->branch_id)->whereMonth('sale_date', '4')->whereYear('sale_date',$request->year)->sum('total');
                $maysales = Sale::where('branch_id', $request->branch_id)->whereMonth('sale_date', '5')->whereYear('sale_date',$request->year)->sum('total');
                $junsales = Sale::where('branch_id', $request->branch_id)->whereMonth('sale_date', '6')->whereYear('sale_date',$request->year)->sum('total');
                $julsales = Sale::where('branch_id', $request->branch_id)->whereMonth('sale_date', '7')->whereYear('sale_date',$request->year)->sum('total');
                $augsales = Sale::where('branch_id', $request->branch_id)->whereMonth('sale_date', '8')->whereYear('sale_date',$request->year)->sum('total');
                $sepsales = Sale::where('branch_id', $request->branch_id)->whereMonth('sale_date', '9')->whereYear('sale_date',$request->year)->sum('total');
                $octsales = Sale::where('branch_id', $request->branch_id)->whereMonth('sale_date', '10')->whereYear('sale_date',$request->year)->sum('total');
                $novsales = Sale::where('branch_id', $request->branch_id)->whereMonth('sale_date', '11')->whereYear('sale_date',$request->year)->sum('total');
                $decsales = Sale::where('branch_id', $request->branch_id)->whereMonth('sale_date', '12')->whereYear('sale_date',$request->year)->sum('total');
                $totalsales = Sale::where('branch_id', $request->branch_id)->whereYear('sale_date',$request->year)->sum('total');
                $array = [
                    'jansales' => number_format($jansales),
                    'febsales' => number_format($febsales),
                    'marsales' => number_format($marsales),
                    'aprsales' => number_format($aprsales),
                    'maysales' => number_format($maysales),
                    'junsales' => number_format($junsales),
                    'julsales' => number_format($julsales),
                    'augsales' => number_format($augsales),
                    'sepsales' => number_format($sepsales),
                    'octsales' => number_format($octsales),
                    'novsales' => number_format($novsales),
                    'decsales' => number_format($decsales),
                    'totalsales' => number_format($totalsales),
                ];
                return $array;
                }else{
                    $sales = DB::table("sale_details")
                    ->join('sales','sales.id','sale_details.sale_id')
                    ->where('sale_details.branch_id', $request->branch_id)
                    ->whereYear('sales.sale_date',$request->year)
                    ->whereMonth('sales.sale_date',$request->month+1)
                    ->select('sales.sale_date as date')
                    ->selectRaw('sum(sale_details.quantity*sale_details.selling_price) as amount')
                    ->groupBy('sales.sale_date')
                    ->orderByRaw('sales.sale_date DESC')
                    ->get();
                }
            }
        }
        else{
            if($request->month == 12){
            $jansales = Sale::where('branch_id', Auth::user()->branch_id)->whereMonth('sale_date', '1')->whereYear('sale_date',$request->year)->sum('total');
            $febsales = Sale::where('branch_id', Auth::user()->branch_id)->whereMonth('sale_date', '2')->whereYear('sale_date',$request->year)->sum('total');
            $marsales = Sale::where('branch_id', Auth::user()->branch_id)->whereMonth('sale_date', '3')->whereYear('sale_date',$request->year)->sum('total');
            $aprsales = Sale::where('branch_id', Auth::user()->branch_id)->whereMonth('sale_date', '4')->whereYear('sale_date',$request->year)->sum('total');
            $maysales = Sale::where('branch_id', Auth::user()->branch_id)->whereMonth('sale_date', '5')->whereYear('sale_date',$request->year)->sum('total');
            $junsales = Sale::where('branch_id', Auth::user()->branch_id)->whereMonth('sale_date', '6')->whereYear('sale_date',$request->year)->sum('total');
            $julsales = Sale::where('branch_id', Auth::user()->branch_id)->whereMonth('sale_date', '7')->whereYear('sale_date',$request->year)->sum('total');
            $augsales = Sale::where('branch_id', Auth::user()->branch_id)->whereMonth('sale_date', '8')->whereYear('sale_date',$request->year)->sum('total');
            $sepsales = Sale::where('branch_id', Auth::user()->branch_id)->whereMonth('sale_date', '9')->whereYear('sale_date',$request->year)->sum('total');
            $octsales = Sale::where('branch_id', Auth::user()->branch_id)->whereMonth('sale_date', '10')->whereYear('sale_date',$request->year)->sum('total');
            $novsales = Sale::where('branch_id', Auth::user()->branch_id)->whereMonth('sale_date', '11')->whereYear('sale_date',$request->year)->sum('total');
            $decsales = Sale::where('branch_id', Auth::user()->branch_id)->whereMonth('sale_date', '12')->whereYear('sale_date',$request->year)->sum('total');
            $totalsales = Sale::where('branch_id', Auth::user()->branch_id)->whereYear('sale_date',$request->year)->sum('total');
            $array = [
                'jansales' => number_format($jansales),
                'febsales' => number_format($febsales),
                'marsales' => number_format($marsales),
                'aprsales' => number_format($aprsales),
                'maysales' => number_format($maysales),
                'junsales' => number_format($junsales),
                'julsales' => number_format($julsales),
                'augsales' => number_format($augsales),
                'sepsales' => number_format($sepsales),
                'octsales' => number_format($octsales),
                'novsales' => number_format($novsales),
                'decsales' => number_format($decsales),
                'totalsales' => number_format($totalsales),
            ];
            return $array;
            }else{
                $sales = DB::table("sale_details")
                ->join('sales','sales.id','sale_details.sale_id')
                ->where('sale_details.branch_id', Auth::user()->branch_id)
                ->whereYear('sales.sale_date',$request->year)
                ->whereMonth('sales.sale_date',$request->month+1)
                ->select('sales.sale_date as date')
                ->selectRaw('sum(sale_details.quantity*sale_details.selling_price) as amount')
                ->groupBy('sales.sale_date')
                ->orderByRaw('sales.sale_date DESC')
                ->get();
            }
        }
        return $sales;
        // if(Auth::user()->branch_id==0){
        //     if($request->month == 0){
        //         $sales = SaleDetail::where('business_id', Auth::user()->business_id)->orderBy("created_at", "desc")
        //         ->whereHas('sale', function ($query) use($request) {
        //             return $query ->whereYear("sale_date", $request->year);
        //         })->with('user','sale','product')->get();
        //     }else{
        //         $sales = SaleDetail::where('business_id', Auth::user()->business_id)->orderBy("created_at", "desc")
        //         ->whereHas('sale', function ($query) use($request) {
        //             return $query->whereMonth("sale_date", $request->month)->whereYear("sale_date", $request->year);
        //         })->with('user','sale','product')->get();
        //     }
        // }
        // else{
        //     if($request->month == 0){
        //         $sales = SaleDetail::where('branch_id', Auth::user()->branch_id)->orderBy("created_at", "desc")
        //         ->whereHas('sale', function ($query) use($request) {
        //             return $query ->whereYear("sale_date", $request->year);
        //         })->with('user','sale','product')->get();
        //     }else{
        //         $sales = SaleDetail::where('branch_id', Auth::user()->branch_id)->orderBy("created_at", "desc")
        //         ->whereHas('sale', function ($query) use($request) {
        //             return $query->whereMonth("sale_date", $request->month)->whereYear("sale_date", $request->year);
        //         })->with('user','sale','product')->get();
        //     }
        // }
        // return SaleDetailResource::collection($sales);
    }

    // Monthly Profits Analysis
    public function monthlyprofits_analysis(Request $request)
    {
        if(Auth::user()->branch_id==0){
            if($request->month == 12){
                $sales = DB::table("sales")
                ->join('sale_details','sale_details.sale_id','sales.id')
                ->where('sale_details.business_id', Auth::user()->business_id)
                ->whereYear('sales.sale_date',$request->year)
                ->select('sales.sale_date as date')
                ->selectRaw('MONTH(sales.sale_date) as salemonth')
                ->selectRaw('ROUND(sum(sale_details.quantity*sale_details.selling_price) - (sum(sale_details.quantity*sale_details.buying_price)+sum(sales.discount)), 0) as profit')
                ->groupByRaw('sales.sale_date')
                ->get();
                }else{
                    $sales = DB::table("sale_details")
                    ->join('sales','sales.id','sale_details.sale_id')
                    ->where('sale_details.business_id', Auth::user()->business_id)
                    ->whereYear('sales.sale_date',$request->year)
                    ->whereMonth('sales.sale_date',$request->month+1)
                    ->select('sales.sale_date as date')
                    ->selectRaw('sum(sale_details.quantity*sale_details.selling_price) as amount')
                    ->selectRaw('sum(sale_details.quantity*sale_details.buying_price) as buyingamount')
                    ->selectRaw('sum(sales.discount) as discounts')
                    ->groupBy('sales.sale_date')
                    ->orderByRaw('sales.sale_date DESC')
                    ->get();
                }
            }else{
                if($request->month == 12){
                $sales = DB::table("sales")
                ->join('sale_details','sale_details.sale_id','sales.id')
                ->where('sale_details.branch_id', Auth::user()->branch_id)
                ->whereYear('sales.sale_date',$request->year)
                ->select('sales.sale_date as date')
                ->selectRaw('MONTH(sales.sale_date) as salemonth')
                ->selectRaw('ROUND(sum(sale_details.quantity*sale_details.selling_price) - (sum(sale_details.quantity*sale_details.buying_price)+sum(sales.discount)), 0) as profit')
                ->groupByRaw('sales.sale_date')
                ->get();
                }else{
                    $sales = DB::table("sale_details")
                    ->join('sales','sales.id','sale_details.sale_id')
                    ->where('sale_details.branch_id', Auth::user()->branch_id)
                    ->whereYear('sales.sale_date',$request->year)
                    ->whereMonth('sales.sale_date',$request->month+1)
                    ->select('sales.sale_date as date')
                    ->selectRaw('sum(sale_details.quantity*sale_details.selling_price) as amount')
                    ->selectRaw('sum(sale_details.quantity*sale_details.buying_price) as buyingamount')
                    ->selectRaw('sum(sales.discount) as discounts')
                    ->groupBy('sales.sale_date')
                    ->orderByRaw('sales.sale_date DESC')
                    ->get();
                }
            }
            return $sales;
        // if(Auth::user()->branch_id==0){
        //     if($request->month == 0){
        //         $sales = SaleDetail::where('business_id', Auth::user()->business_id)->orderBy("created_at", "desc")
        //         ->whereHas('sale', function ($query) use($request) {
        //             return $query ->whereYear("sale_date", $request->year);
        //         })->with('user','sale','product')->get();
        //     }else{
        //         $sales = SaleDetail::where('business_id', Auth::user()->business_id)->orderBy("created_at", "desc")
        //         ->whereHas('sale', function ($query) use($request) {
        //             return $query->whereMonth("sale_date", $request->month)->whereYear("sale_date", $request->year);
        //         })->with('user','sale','product')->get();
        //     }
        // }
        // else{
        //     if($request->month == 0){
        //         $sales = SaleDetail::where('branch_id', Auth::user()->branch_id)->orderBy("created_at", "desc")
        //         ->whereHas('sale', function ($query) use($request) {
        //             return $query ->whereYear("sale_date", $request->year);
        //         })->with('user','sale','product')->get();
        //     }else{
        //         $sales = SaleDetail::where('branch_id', Auth::user()->branch_id)->orderBy("created_at", "desc")
        //         ->whereHas('sale', function ($query) use($request) {
        //             return $query->whereMonth("sale_date", $request->month)->whereYear("sale_date", $request->year);
        //         })->with('user','sale','product')->get();
        //     }
        // }
        // return SaleDetailResource::collection($sales);
    }


    // Order pedning Status
    public function sales_status()
    {
        if(Auth::user()->branch_id==0){
            $kitchencart = SaleCart::where('business_id',Auth::user()->business_id)->where('hold',1)->where('cart_status',1)->orderBy("created_at", "asc")->get();
        }
        else{
            $kitchencart = SaleCart::where('branch_id',Auth::user()->branch_id)->where('hold',1)->where('cart_status',1)->orderBy("created_at", "asc")->get();
        }
        return SaleCartResource::collection($kitchencart);
    }


    // Sales Status
    public function readyorders()
    {
        if(Auth::user()->branch_id==0){
            $kitchencart = SaleCart::where('business_id',Auth::user()->business_id)->where('hold',1)->where('cart_status',2)->orderBy("created_at", "asc")->get();
        }
        else{
            $kitchencart = SaleCart::where('branch_id',Auth::user()->branch_id)->where('hold',1)->where('cart_status',2)->orderBy("created_at", "asc")->get();
        }
        return SaleCartResource::collection($kitchencart);
    }



    // Sales analysis report
    public function salesanalysis_report(Request $request)
    {
        if(Auth::user()->branch_id==0){
            if($request->branch_id == 0){
                $sales = Sale::where('business_id', Auth::user()->business_id)->whereBetween("sale_date", [$request->datefrom, $request->dateto])->orderBy("created_at", "desc")->get();
            }else{
                $sales = Sale::where('branch_id', $request->branch_id)->whereBetween("sale_date", [$request->datefrom, $request->dateto])->orderBy("created_at", "desc")->get();
            }
        }
        else{
            $sales = Sale::where('branch_id', Auth::user()->branch_id)->whereBetween("sale_date", [$request->datefrom, $request->dateto])->orderBy("created_at", "desc")->get();
        }
        return SaleResource::collection($sales);
    }


    // Confirm Ready Order
    public function confirmorder(Request $request)
    {
        $sales = SaleCart::where('branch_id', Auth::user()->branch_id)->orderBy("created_at", "desc")->where('id', $request->id)->first();
        $sales->cart_status = 2;
        $sales->save();
        return response()->json(["msg" => "Order successfully confirmed ready"], 200);
    }

    // Confirm Served Order
    public function confirmserved(Request $request)
    {
        $sales = SaleCart::where('branch_id', Auth::user()->branch_id)->orderBy("created_at", "desc")->where('id', $request->id)->first();
        $sales->cart_status = 3;
        $sales->save();
        return response()->json(["msg" => "Order successfully confirmed ready"], 200);
    }


    // Product Demand
    public function product_demand(Request $request)
    {
        if(Auth::user()->branch_id==0){
        $sales = DB::table("sale_details")
            ->join('products','products.id','sale_details.product_id')
            ->join('product_units','product_units.id','sale_details.unit')
            ->join('sales','sales.id','sale_details.sale_id')
            ->where('sale_details.business_id', Auth::user()->business_id)
            ->whereBetween('sales.sale_date',[$request->datefrom, $request->dateto])
            ->select('products.product_name')
            ->selectRaw('sum(sale_details.quantity) as quantity')
            ->selectRaw('sum(sale_details.quantity*sale_details.selling_price) as amount')
            ->groupBy('products.product_name')
            ->orderByRaw('sum(sale_details.quantity*sale_details.selling_price) DESC')
            ->get();
        }
        else{
        $sales = DB::table("sale_details")
            ->join('products','products.id','sale_details.product_id')
            ->join('product_units','product_units.id','sale_details.unit')
            ->join('sales','sales.id','sale_details.sale_id')
            ->where('sale_details.branch_id', Auth::user()->branch_id)
            ->whereBetween('sales.sale_date',[$request->datefrom, $request->dateto])
            ->select('products.product_name')
            ->selectRaw('sum(sale_details.quantity) as quantity')
            ->selectRaw('sum(sale_details.quantity*sale_details.selling_price) as amount')
            ->groupBy('products.product_name')
            ->orderByRaw('sum(sale_details.quantity*sale_details.selling_price) DESC')
            ->get();
        }
        return $sales;
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


    public function sendSMSAPI($contact, $message, $smsSize, $smsType)
    {
        //fetch sms balance
        $smsBalance = SmsBalance::where("business_id", Auth::user()->business_id)->where("branch_id", Auth::user()->branch_id)->first()->amount ?? 0;
        //use the API to send message
        $data = 'api_id='.config('services.uganda_sms.api_id').'&api_password='.config('services.uganda_sms.password').'&sms_type=P&encoding=T&sender_id=BULKSMS&phonenumber=256' . $contact . '&textmessage=' . rawurlencode($message);
        // Send the GET request with cURL
        $ch = curl_init('http://apidocs.speedamobile.com/api/SendSMS?' . $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        //Accessing the Decoded Values from the Json
        $obj = json_decode($response);
        if (is_object($obj)) {
               //Removing the sent SMS
        SmsBalance::where("business_id", Auth::user()->business_id)->where("branch_id", Auth::user()->branch_id)->update([
            "amount" => intVal($smsBalance) - intVal($smsSize),
        ]);
          Messaging::create([
            "business_id" => Auth::user()->business_id,
            "branch_id" => Auth::user()->branch_id,
            "sender" => Auth::user()->id,
            "receiver" => $contact,
            "message_type" => $smsType,
            "message" => $message,
            "size" => $smsSize,
            "message_id" => $obj->message_id
        ]);
        // Capture log
        $action = 'Sent sms message to ' . $contact;
        // $this->functionmaster->log($action,Log::Finance);
        $this->functionmaster->log($action,Log::Finance);
           return $smsid = $obj->message_id;
        } else {
            return response()->json(["msg" => "Error Occurred"], 200);
        }



    }

    public function appsales(Request $request)
    {
        // return $request;
        $request->validate([
            'paid' => 'required',
            'total' => 'required',
            'receipt' => 'required',
        ]);

        $receiptid = Sale::where('branch_id',Auth::user()->branch_id)->where("offline_receipt", $request->receipt)->first();
        if($receiptid){
            return response(['sale_id'=> $request->sale_id, 'status'=> 200]);
        }

        $discount = str_replace(",","",$request->discount ?? 0);
        $total = $request->total-$discount;
        // generate receipt
         $receiptdet = $this->functionmaster->generateReceipt();
         $recno = $receiptdet['recno'];
         $receipt = $receiptdet['receipt'];
        if($request->customer && $total>str_replace(",","",$request->paid)){
            $bal = $this->functionmaster->customerBalance($request->customer);
            if($bal>0 && $bal>= ($total-str_replace(",","",$request->paid))){
                $paid = $total;
            }
            else if($bal>0 && $bal< ($total-str_replace(",","",$request->paid))){
                $paid = str_replace(",","",$request->paid)+$bal;
            }else if(str_replace(",","",$request->paid) > $total){
                $paid = $total;
            }else{
                $paid = str_replace(",","",$request->paid);
            }

        }
        else{
            $paid = str_replace(",","",$request->paid);
        }
        if($paid > $total){
            $paid = $total;
        }
        $sale = Sale::create([
            'customer_id' => $request->customer,
            'total' => $total,
            'paid' => $paid ?? 0,
            'discount' => $discount??0,
            'received' =>  $request->paid?str_replace(",","",$request->paid):0,
            'sale_date' => $request->sale_date ?? date('Y-m-d'),
            'receipt' => $receipt,
            'offline_receipt' => $request->receipt,
            'picking_date' => $request->picking_date??'',
            'user_id' => Auth::user()->id,
            'transaction_status' => $request->transaction_status??0,
            'branch_id' => Auth::user()->branch_id,
            'business_id' => Auth::user()->business_id,
        ]);
        if($total > str_replace(",","",$request->paid)){
            $acc_paid = str_replace(",","",$request->paid);
        }else{
            $acc_paid = $total;
        }

        $option = PaymentOption::where('branch_id',Auth::user()->branch_id)->where("is_default", 1)->first('id');
        $optiondetails = PaymentOption::where('branch_id',Auth::user()->branch_id)->where('id', $request->mode)->first();

        $spayment = Payment::create([
            'sale_id' => $sale->id,
            'amount' => $acc_paid,
            'mode' => $request->mode && $optiondetails?$request->mode:$option->id,
            'receipt' => $sale->receipt,
            'date' => $sale->sale_date,
            'recno' => $recno,
            'month' => (int)date("m"),
            'year' => date("y"),
            'user_id' => $sale->user_id,
            'transaction_status' => $request->transaction_status??0,
            'branch_id' => $sale->branch_id,
            'business_id' => $sale->business_id,
        ]);

        if(str_replace(",","",$request->paid)>0){
            // insert into transactions
            $this->functionmaster->transaction(1,$spayment->id,$spayment->amount,0,$spayment->mode,"Sale of Products",$spayment->date);
        }

        if($request->customer && $total>str_replace(",","",$request->paid)){
            CustomerAccount::create([
                'customer_id' => $request->customer,
                'amount_in' => $paid > $total?$total:str_replace(",","",$request->paid),
                'amount_out' => $total,
                'tId' => $spayment->id,
                'mode' => $spayment->mode,
                'date' => $spayment->date,
                'description' => "Partial Payment for items of ".$total,
                'user_id' => $sale->user_id,
                'transaction_status' => $request->transaction_status??0,
                'branch_id' => $sale->branch_id,
                'business_id' => $sale->business_id,
            ]);
        }
            $no = 0;
            $products = [];
            $quatity = [];
            $cartItems = $request->cartItems;
            foreach ($cartItems as $item){
                // return $item['product_id';
                $productname = Product::find($item['product_id'])->product_name;
                array_push($products, $productname);
                array_push($quatity, $item['quantity']);
                $no++;
                $type = $item['type'];
                if($type == 1){
                    $unitqty = ProductUnit::find($item['unit'])->base_quantity;
                }
                else{
                    $unitqty = 1;
                }
                $bprice = Stock::where('product_id', $item['product_id'])->where('branch_id', Auth::user()->branch_id)->first()->buying_price ?? 0;

                $sd = SaleDetail::create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'quantity_remaining' => $item['quantity'] - $item['quantity_taken'],
                    'buying_price' => $bprice*$unitqty,
                    'selling_price' => $item['selling_price'],
                    'unit' => $item['unit'],
                    'batch_id' => $item['batch'],
                    'description' => $item['description']??'',
                    'sale_id' => $sale->id,
                    'user_id' => Auth::user()->id,
                    'branch_id' => Auth::user()->branch_id,
                    'business_id' => Auth::user()->business_id,
                ]);

                if($request['batch'] && $request['batch']!=''){
                    $batchcheck = Batch::where('id',$sd->batch_id)->where('product_id',$sd->product_id)->where('type',0)
                    ->where('branch_id', Auth::user()->branch_id)->first();
                    if($batchcheck){
                        $batchcheck->quantity_in = $batchcheck->quantity_in-$sd->quantity;
                        $batchcheck->save();
                    }
                }
                if($type==1){
                    // Update product quantity
                    $this->functionmaster->updateStockLevel($item['product_id'],-($item['quantity']*$unitqty));
                    // insert into stock movement
                    $this->functionmaster->stockmove($item['product_id'],StockMovement::Sale,0,$item['quantity']*$unitqty,$sd->id);
                }
            }
            // Sending appreciation message
            $setting = GeneralSetting::where('branch_id', Auth::user()->branch_id)->first();

            $newpdts = json_encode($products);
            $newqtys = json_encode($quatity);

            if($request->customer && $setting->send_appreciation == 1){
                // ====== SEND SMS ===========
                $smsSize = ceil(strlen($setting->customer_message) / 160);
                $totalSMS = intVal($smsSize);
                //fetch sms balance
                $smsBalance = SmsBalance::where("business_id", Auth::user()->business_id)->where("branch_id", Auth::user()->branch_id)->first()->amount ?? 0;
                if ($smsBalance >= $totalSMS) {
                    $reciepients = $request['recievers'];
                    //sending the sms
                    $contact = substr(Customer::find($request['customer'])->contact, -9);
                    //using the message api function
                    $this->sendSMSAPI($contact, $setting->customer_message, $smsSize, 'Sales');
                } else {
                    return response()->json(["msg" => "You Have Few SMS, " . $totalSMS . ": SMS Needed, " . $smsBalance . ": SMS Remaining. Please request for more SMS",'sale_id'=> $request->sale_id, 'status'=> 200]);
                }
            }
            if($request->customer && $setting->message_saledetails == 1){
                $salemessage = 'You have placed an order of '.$newpdts.' with quantities '.$newqtys.' worth '.$total.'. Paid '.$paid.'. Balance of '.$total-$paid.'. Thank you for supporting '.Business::find(Auth::user()->business_id)->name;
                $smsSize = ceil(strlen($salemessage) / 160);
                $totalSMS = intVal($smsSize);
                //fetch sms balance
                $smsBalance = SmsBalance::where("business_id", Auth::user()->business_id)->where("branch_id", Auth::user()->branch_id)->first()->amount ?? 0;
                if ($smsBalance >= $totalSMS) {
                    $reciepients = $request['recievers'];
                    //sending the sms
                    $contact = substr(Customer::find($request['customer'])->contact, -9);
                    //using the message api function
                    $this->sendSMSAPI($contact, $salemessage, $smsSize, 'Sales');
                } else {
                    return response()->json(["msg" => "You Have Few SMS, " . $totalSMS . ": SMS Needed, " . $smsBalance . ": SMS Remaining. Please request for more SMS",'sale_id'=> $request->sale_id, 'status'=> 200]);
                }
            }
            // insert into logs
            $this->functionmaster->log("Recorded a sale of $no products $newpdts with quantities $newqtys worth $total", Log::Sale);
            return response(['sale_id'=> $request->sale_id, 'status'=> 200]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'paid' => 'required',
            'total' => 'required',
        ]);
        $discount = str_replace(",","",$request->discount ?? 0);
        $total = $request->total-$discount;

        $cart = SaleCart::where('user_id', Auth::user()->id)->where('branch_id', Auth::user()->branch_id)->where('hold',0)->orderBy("created_at", "desc")->get();
        if($cart->count() > 0){
            try{
                $setting = GeneralSetting::where('branch_id', Auth::user()->branch_id)->first();
                // generate receipt
                $receiptdet = $this->functionmaster->generateReceipt();
                $recno = $receiptdet['recno'];
                $receipt = $receiptdet['receipt'];

                $option = PaymentOption::where('branch_id',Auth::user()->branch_id)->where("is_default", 1)->first('id');
                $optiondetails = PaymentOption::where('branch_id',Auth::user()->branch_id)->where('id', $request->mode)->first();

                if($request->customer && $total>str_replace(",","",$request->paid)){
                    $bal = $this->functionmaster->customerBalance($request->customer);
                    if($bal>0 && $bal>= ($total-str_replace(",","",$request->paid))){
                        $paid = $total;
                    }
                    else if($bal>0 && $bal< ($total-str_replace(",","",$request->paid))){
                        $paid = str_replace(",","",$request->paid)+$bal;
                    }else if(str_replace(",","",$request->paid) > $total){
                        $paid = $total;
                    }else{
                        $paid = str_replace(",","",$request->paid);
                    }

                }
                elseif($request->customer && $total<str_replace(",","",$request->paid) && $setting->deposit_balances == 1){
                    $deposit = str_replace(",","",$request->paid)-$total;
                    $cdeposit=CustomerDeposit::create([
                        'customer_id' => $request->customer,
                        'amount' => round($deposit, 2),
                        'date' => $request->date ?? date('Y-m-d'),
                        'mode' => $request->mode && $optiondetails?$request->mode:$option->id,
                        'user_id' => Auth::user()->id,
                        'branch_id' => Auth::user()->branch_id,
                        'business_id' => Auth::user()->business_id,
                    ]);
                    CustomerAccount::create([
                        'customer_id' => $cdeposit->customer_id,
                        'amount_in' => $cdeposit->amount,
                        'amount_out' => 0,
                        'category' => 2,
                        'date' => $cdeposit->date,
                        'mode' => $cdeposit->mode,
                        'tId' => $cdeposit->id,
                        'description' => 'Customer Deposit',
                        'user_id' => Auth::user()->id,
                        'branch_id' => Auth::user()->branch_id,
                        'business_id' => Auth::user()->business_id,

                    ]);

                    $this->functionmaster->transaction(9,$cdeposit->id,$cdeposit->amount,0,$cdeposit->mode,"Recorded a customer Deposit",$cdeposit->date);

                    $action = "Recorded a  Customer deposit of: ".$deposit." From ".Customer::find($cdeposit->customer_id)->name;
                    $this->functionmaster->log($action,Log::Finance);

                    $paid = $total;
                }
                else{
                    $paid = str_replace(",","",$request->paid);
                }
                if($paid > $total){
                    $paid = $total;
                }

                // $olddata = Sale::where('total', $total)->where('paid', $paid)->where('sale_date', $request->sale_date)->where('receipt', $receipt-1)
                // ->where('branch_id', Auth::user()->branch_id)->where('user_id', Auth::user()->id)->where('created_at', date('Y-m-d H:i:s'))->first();

                // if($olddata){
                //     return response(['message'=>"This sale has already been recorded"],200);
                // }

                $sale = Sale::create([
                    'customer_id' => $request->customer,
                    'total' => $total,
                    'paid' => $paid ?? 0,
                    'discount' => $discount??0,
                    'received' =>  $request->paid?str_replace(",","",$request->paid):0,
                    'sale_date' => $request->sale_date ?? date('Y-m-d'),
                    'receipt' => $receipt,
                    'picking_date' => $request->picking_date??'',
                    'user_id' => Auth::user()->id,
                    'new_status' => 2,
                    'transaction_status' => $request->transaction_status??0,
                    'branch_id' => Auth::user()->branch_id,
                    'business_id' => Auth::user()->business_id,
                ]);
                if($total > str_replace(",","",$request->paid)){
                    $acc_paid = str_replace(",","",$request->paid);
                }else{
                    $acc_paid = $total;
                }

                $spayment = Payment::create([
                    'sale_id' => $sale->id,
                    'amount' => $acc_paid,
                    'mode' => $request->mode && $optiondetails?$request->mode:$option->id,
                    'receipt' => $sale->receipt,
                    'date' => $sale->sale_date,
                    'recno' => $recno,
                    'month' => (int)date("m"),
                    'year' => date("y"),
                    'user_id' => $sale->user_id,
                    'transaction_status' => $request->transaction_status??0,
                    'branch_id' => $sale->branch_id,
                    'business_id' => $sale->business_id,
                ]);

                if(str_replace(",","",$request->paid)>0){
                    // insert into transactions
                    $this->functionmaster->transaction(1,$spayment->id,$spayment->amount,0,$spayment->mode,"Sale of Products",$spayment->date);
                }

                if($request->customer && $total>str_replace(",","",$request->paid)){
                    CustomerAccount::create([
                        'customer_id' => $request->customer,
                        'amount_in' => $spayment->amount,
                        'amount_out' => $total,
                        'tId' => $spayment->id,
                        'mode' => $spayment->mode,
                        'date' => $spayment->date,
                        'description' => "Partial Payment for items of ".$total,
                        'user_id' => $sale->user_id,
                        'transaction_status' => $request->transaction_status??0,
                        'branch_id' => $sale->branch_id,
                        'business_id' => $sale->business_id,
                    ]);
                }
                $no = 0;
                $products = [];
                $quatity = [];
                foreach ($cart as $item){
                    $productname = Product::find($item->product_id)->product_name;
                    array_push($products, $productname);
                    array_push($quatity, $item->quantity);
                    $no++;
                    $type = $item->type;
                    if($type == 1){
                        $unitqty = ProductUnit::find($item->unit)->base_quantity;
                    }
                    else{
                        $unitqty = 1;
                    }
                    $bprice = Stock::where('product_id', $item->product_id)->where('branch_id', Auth::user()->branch_id)->first();

                    $sd = SaleDetail::create([
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'quantity_remaining' => $item->quantity - $item->quantity_taken,
                        'buying_price' => $bprice?$bprice->buying_price*$unitqty:0,
                        'average_buyingprice' => $bprice?$bprice->average_buyingprice*$unitqty:0,
                        'selling_price' => $item->selling_price,
                        'unit' => $item->unit,
                        'batch_id' => $item->batch_id,
                        'description' => $item->description??'',
                        'sale_id' => $sale->id,
                        'user_id' => Auth::user()->id,
                        'branch_id' => Auth::user()->branch_id,
                        'business_id' => Auth::user()->business_id,
                    ]);

                    if($request->batch && $request->batch!=''){
                        $batchcheck = Batch::where('id',$sd->batch_id)->where('product_id',$sd->product_id)->where('type',0)
                        ->where('branch_id', Auth::user()->branch_id)->first();
                        if($batchcheck){
                            $batchcheck->quantity_in = $batchcheck->quantity_in-$sd->quantity;
                            $batchcheck->save();
                        }
                    }
                    if($type==1){
                        // Update product quantity
                        $this->functionmaster->updateStockLevel($item->product_id,-($item->quantity*$unitqty));
                        // insert into stock movement
                        $this->functionmaster->stockmove($item->product_id,StockMovement::Sale,0,$item->quantity*$unitqty,$sd->id);
                    }

                    // Delete from cart
                    SaleCart::find($item->id)->delete();
                }
                // Sending appreciation message

                $newpdts = json_encode($products);
                $newqtys = json_encode($quatity);

                if($request->customer && $setting->send_appreciation == 1){
                    // ====== SEND SMS ===========
                    $smsSize = ceil(strlen($setting->customer_message) / 160);
                    $totalSMS = intVal($smsSize);
                    //fetch sms balance
                    $smsBalance = SmsBalance::where("business_id", Auth::user()->business_id)->where("branch_id", Auth::user()->branch_id)->first()->amount ?? 0;
                    if ($smsBalance >= $totalSMS) {
                        $reciepients = $request->recievers;
                        //sending the sms
                        $contact = substr(Customer::find($request->customer)->contact, -9);
                        //using the message api function
                        $this->sendSMSAPI($contact, $setting->customer_message, $smsSize, 'Sales');
                    } else {
                        return response()->json(["msg" => "You Have Few SMS, " . $totalSMS . ": SMS Needed, " . $smsBalance . ": SMS Remaining. Please request for more SMS", "status" => 500]);
                    }
                }
                if($request->customer && $setting->message_saledetails == 1){
                    $salemessage = 'You have placed an order of '.$newpdts.' with quantities '.$newqtys.' worth '.$total.'. Paid '.$paid.'. Balance of '.$total-$paid.'. Thank you for supporting '.Business::find(Auth::user()->business_id)->name;
                    $smsSize = ceil(strlen($salemessage) / 160);
                    $totalSMS = intVal($smsSize);
                    //fetch sms balance
                    $smsBalance = SmsBalance::where("business_id", Auth::user()->business_id)->where("branch_id", Auth::user()->branch_id)->first()->amount ?? 0;
                    if ($smsBalance >= $totalSMS) {
                        $reciepients = $request->recievers;
                        //sending the sms
                        $contact = substr(Customer::find($request->customer)->contact, -9);
                        //using the message api function
                        $this->sendSMSAPI($contact, $salemessage, $smsSize, 'Sales');
                    } else {
                        return response()->json(["msg" => "You Have Few SMS, " . $totalSMS . ": SMS Needed, " . $smsBalance . ": SMS Remaining. Please request for more SMS", "status" => 500]);
                    }
                }
                // insert into logs
                $this->functionmaster->log("Recorded a sale of $no products $newpdts with quantities $newqtys worth $total", Log::Sale);
                return response(['id'=> $sale->id, 'status'=> 200]);

            }catch (\Exception $e) {
                // Delete a sale
                Sale::where('id',$sale->id)->where('branch_id',Auth::user()->branch_id)->first()?Sale::where('id',$sale->id)->where('branch_id',Auth::user()->branch_id)->delete():'';
                // Delete stock movement
                StockMovement::where("transaction_id",$sale->id)->where('type',1)->where('branch_id',Auth::user()->branch_id)->first()?StockMovement::where("transaction_id",$sale->id)->where('type',1)->where('branch_id',Auth::user()->branch_id)->delete():'';
                // Delete sale
                $payments = Payment::where('sale_id',$sale->id)->where('branch_id',Auth::user()->branch_id)->get();
                if($payments){
                    foreach($payments as $payment){
                        $pid = $payment->id;
                        CustomerAccount::where('tId',$pid)->where('category',1)->first()?CustomerAccount::where('tId',$pid)->where('category',1)->delete():'';
                        $payment->delete();
                        Transaction::where('tId',$pid)->where('category',1)->where('branch_id',Auth::user()->branch_id)->first()?Transaction::where('tId',$pid)->where('category',1)->where('branch_id',Auth::user()->branch_id)->delete():'';
                    }
                }

                $sales = SaleDetail::where('sale_id',$sale->id)->where('branch_id',Auth::user()->branch_id)->get();

                if($sales){
                    foreach($sales as $detail){
                        $unitdet = ProductUnit::where('id',$detail->unit)->where('branch_id',Auth::user()->branch_id)->first();
                        $batchx = $detail->batch_id;
                        $product = $detail->product_id;
                        $type = Product::find($product)->is_product;
                        if($type==1){
                            $qty = $detail->quantity*$unitdet->base_quantity;
                        }

                        // update batch
                        if($batchx!=''){
                            $batchdet = Batch::where('id',$batchx)->where('product_id',$product)->where('branch_id',Auth::user()->branch_id)
                                ->where('type',0)->first();
                            $batchdet->quantity_in = $batchdet->quantity_in+$qty;
                            $batchdet->save();
                        }
                        if($type==1){
                            // Update product quantity
                            $this->functionmaster->updateStockLevel($product,$qty);
                        }

                        $salerecord = SaleCart::where('product_id', $detail->product_id)->where('user_id', Auth::user()->id)->first();
                        if(!$salerecord){
                            SaleCart::create([
                                'product_id' => $detail->product_id,
                                'quantity' => $detail->quantity,
                                'quantity_taken' => $detail->quantity-$detail->quantity_remaining,
                                'selling_price' => $detail->selling_price,
                                'batch_id' => $detail->batch_id,
                                'unit' => $detail->unit,
                                'type' => Product::find($detail->product_id)->is_product,
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
            return response(['message'=>"No items in the cart"],202);
        }
    }

    // ======================= Return Sale ========================
    public function return_sales(Request $request)
    {
        // return $request;
        $request->validate([
            'date' => 'required',
            'quantity' => 'required',
            'reason' => 'required',
            'mode' => 'required',
            'sale_id' => 'required',
            'product_id' => 'required'
        ]);
        $oldreturn = SaleReturn::where('sale_id', $request->sale_id)->where('branch_id', Auth::user()->branch_id)->where('product_id', $request->product_id)->first();
        if($oldreturn){
            $message = 'This Product return has already been recorded for this sale';
        }else{
            $saledetail = SaleDetail::where('sale_id', $request->sale_id)->where('branch_id', Auth::user()->branch_id)->where('product_id', $request->product_id)->first();
            $sale = Sale::where('id', $request->sale_id)->first();
            $sale->total = $sale->total-($request->quantity*$saledetail->selling_price);
            if($sale->paid >= ($request->quantity*$saledetail->selling_price)){
                $sale->paid = $sale->paid-($request->quantity*$saledetail->selling_price);
            }else{
                $sale->paid = 0;
            }
            $saledetail->quantity = $saledetail->quantity-$request->quantity;
            $return = SaleReturn::create([
                'sale_id' => $request->sale_id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'unit_selling_price' => $saledetail->selling_price,
                'amount_refunded' => $sale->paid >= ($request->quantity*$saledetail->selling_price)?($request->quantity*$saledetail->selling_price):$sale->paid,
                'date' => $request->date,
                'reason' => $request->reason,
                'mode' => $request->mode,
                'user_id' => Auth::user()->id,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
            ]);
            $sale->save();
            $saledetail->save();
            // insert into transactions
            $this->functionmaster->transaction(4,$return->id,0,$return->amount_refunded,$request->mode,"Sales Returns",$return->date);
            $saledetail->returned = 1;
            $saledetail->save();
            $this->functionmaster->updateStockLevel($request->product_id,$request->quantity);
            $message = 'Product Return recorded successfully';
            // insert into logs
            $product = Product::where('id', $request->product_id)->where('branch_id', Auth::user()->branch_id)->first();
            $this->functionmaster->log("Recorded a sale return of product ".$product->product_name. "quantity ".$request->quantity, Log::Sale);
        }
        return response(['message'=> $message, 'status'=> 200]);
    }


    public function pick_sales(Request $request)
    {
        // return $request;
        $request->validate([
            'date' => 'required',
            'sale_id' => 'required',
        ]);

        $sale = Sale::where('id', $request->sale_id)->where('branch_id', Auth::user()->branch_id)->first();
        $sale->picked = 1;
        $sale->date_picked = $request->date;
        $sale->update();
        $message = 'Product picked recorded successfully';
        // insert into logs
        $product = Product::where('id', $request->product_id)->where('branch_id', Auth::user()->branch_id)->first();
        $this->functionmaster->log("Recorded items picked successfully", Log::Sale);
        return response(['message'=> $message, 'status'=> 200]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sale = Sale::find($id);
        return new SaleResource($sale);
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
        $payments = Payment::where('branch_id',Auth::user()->branch_id)->where('sale_id',$id)->count();
        if($payments > 1){
            return response(['message'=>"This Sale has more than one payment. Delete it instead"]);
        }
        else{
            $sale = Sale::where('id',$id)->where('branch_id',Auth::user()->branch_id)->first();
            $saledet = SaleDetail::where('sale_id',$id)->where('branch_id',Auth::user()->branch_id)->orderBy("created_at", "asc")->get();
            // Delete stock movement
            StockMovement::where("transaction_id",$id)->where('type',1)->where('branch_id',Auth::user()->branch_id)->delete();
            // Delete sale
            $sale->delete();
            $payment = Payment::where('sale_id',$id)->where('branch_id',Auth::user()->branch_id)->first();
            // Delete transaction
            Transaction::where("tId",$payment->id)->where('category',1)->where('branch_id',Auth::user()->branch_id)->delete();
            CustomerAccount::where("tId",$payment->id)->where('category',1)->delete();
            $payment->delete();

            foreach($saledet as $detail){
                $unitdet = ProductUnit::where('id',$detail->unit)->where('branch_id',Auth::user()->branch_id)->first();
                $batchx = $detail->batch_id;
                $product = $detail->product_id;
                $type = Product::find($product)->is_product;
                if($type==1){
                    $qty = $detail->quantity*$unitdet->base_quantity;
                }
                else{
                    $qty=0;
                }
                // update batch
                if($batchx!=''){
                    $batchdet = Batch::where('id',$batchx)->where('product_id',$product)->where('branch_id',Auth::user()->branch_id)
                    ->where('type',0)->first();
                    $batchdet->quantity_in = $batchdet->quantity_in+$qty;
                    $batchdet->save();
                }
                // Update product quantity
                $this->functionmaster->updateStockLevel($product,$qty);
                // Insert into cart
                SaleCart::create([
                    'product_id' => $product,
                    'quantity' => $detail->quantity,
                    'quantity_taken' => $detail->quantity-$detail->quantity_remaining,
                    'selling_price' => $detail->selling_price,
                    'batch_id' => $detail->batch_id,
                    'unit' => $detail->unit,
                    'type' => $type,
                    'user_id' => Auth::user()->id,
                    'branch_id' => Auth::user()->branch_id,
                    'business_id' => Auth::user()->business_id,
                ]);
            }
            SaleDetail::where('sale_id',$id)->where('branch_id',Auth::user()->branch_id)->delete();
            // insert into logs
            $action = "Edited a sale that was recorded on ".$sale->created_at;
            $this->functionmaster->log($action, Log::Sale);
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
        // Delete a sale
        $sale = Sale::where('id',$id)->where('branch_id',Auth::user()->branch_id)->first();
        // Delete stock movement
        StockMovement::where("transaction_id",$id)->where('type',1)->where('branch_id',Auth::user()->branch_id)->delete();
        // Delete sale
        $sale->delete();
        $payments = Payment::where('sale_id',$id)->where('branch_id',Auth::user()->branch_id)->get();
        foreach($payments as $payment){
            $pid = $payment->id;
            CustomerAccount::where('tId',$pid)->where('category',1)->delete();
            $payment->delete();
            Transaction::where('tId',$pid)->where('category',1)->where('branch_id',Auth::user()->branch_id)->delete();
        }

        $sales = SaleDetail::where('sale_id',$id)->where('branch_id',Auth::user()->branch_id)->get();
        foreach($sales as $detail){
            $unitdet = ProductUnit::where('id',$detail->unit)->where('branch_id',Auth::user()->branch_id)->first();
            $batchx = $detail->batch_id;
            $product = $detail->product_id;
            $type = Product::find($product)->is_product;
            if($type==1){
                $qty = $detail->quantity*$unitdet->base_quantity;
            }
            else{
                $qty=0;
            }


            // update batch
            if($batchx!=''){
                $batchdet = Batch::where('id',$batchx)->where('product_id',$product)->where('branch_id',Auth::user()->branch_id)
                    ->where('type',0)->first();
                $batchdet->quantity_in = $batchdet->quantity_in+$qty;
                $batchdet->save();
            }
            if($type==1){
                // Update product quantity
                $this->functionmaster->updateStockLevel($product,$qty);
            }
        }
        SaleDetail::where('sale_id',$id)->where('branch_id',Auth::user()->branch_id)->delete();
        // insert into logs
        $action = "Deleted a sale that was recorded on ".$sale->created_at;
        $this->functionmaster->log($action, Log::Sale);


    }

    // Updating amount paid and mode
    public function updateAmtPaid(Request $request, $id){
        $request->validate([
            'paid' => 'required',
        ]);
        $payments = Payment::where('branch_id',Auth::user()->branch_id)->where('sale_id',$id)->count();
        $paymentAmount = Payment::where('branch_id',Auth::user()->branch_id)->where('sale_id',$id)->sum('amount');

        if($payments > 1){
            return response(['message'=>"This Sale has more than one payment. Delete it instead"],201);
        }else if($paymentAmount == 0){
            return response(['message'=>"This Sale has no payment to be changed. Please record a payment in debtors or Delete it instead"],201);

        }
        else{
            $Sale = Sale::find($id);
            if(str_replace(',', '', $request->paid)>($Sale->total - $Sale->discount)){
            return response(['message'=>"Amount Paid Cannot be greater than Sale Total"],201);

            }
            $Sale->paid = str_replace(',', '', $request->paid);
            $Sale->save();
            // Update Transaction
            $spaymentId = Payment::where('sale_id',$id)->where('branch_id',Auth::user()->branch_id)->first()->id;
            Transaction::where('category',1)->where('tId',$spaymentId)->where('branch_id',Auth::user()->branch_id)
            ->update(['amount_in'=>str_replace(',', '', $request->paid)]);
            // Update payment
            Payment::where('branch_id',Auth::user()->branch_id)->where('sale_id',$id)
            ->update(['amount'=>str_replace(',', '', $request->paid)]);
            CustomerAccount::where("tId",$spaymentId)->where('category',1)
        ->update(['amount_in' => str_replace(',', '',$request->paid)]);
        }
    }

    public function debtors(Request $request){
        if(Auth::user()->branch_id==0){
            if($request->branch_id == 0){
                if($request->customer == ''){
                    $debtors = Sale::whereRaw('total > paid')->where('business_id', Auth::user()->business_id)->whereBetween("sale_date", [$request->datefrom, $request->dateto])->orderBy("created_at", "desc")->get();
                }elseif($request->customer != ''){
                    $debtors = Sale::whereRaw('total > paid')->where('business_id', Auth::user()->business_id)->where('branch_id', $request->branch_id)->whereBetween("sale_date", [$request->datefrom, $request->dateto])
                    ->where('customer_id', $request->customer)->orderBy("created_at", "desc")->get();
                }
            }else{
                if($request->customer == ''){
                    $debtors = Sale::whereRaw('total > paid')->where('business_id', Auth::user()->business_id)->where('branch_id', $request->branch_id)->whereBetween("sale_date", [$request->datefrom, $request->dateto])->orderBy("created_at", "desc")->get();
                }elseif($request->customer != ''){
                    $debtors = Sale::whereRaw('total > paid')->where('business_id', Auth::user()->business_id)->where('branch_id', $request->branch_id)->whereBetween("sale_date", [$request->datefrom, $request->dateto])
                    ->where('customer_id', $request->customer)->orderBy("created_at", "desc")->get();
                }
            }
        }else{
            if($request->customer == ''){
                $debtors = Sale::whereRaw('total > paid')->where('branch_id', Auth::user()->branch_id)->whereBetween("sale_date", [$request->datefrom, $request->dateto])->orderBy("created_at", "desc")->get();
            }elseif($request->customer != ''){
                $debtors = Sale::whereRaw('total > paid')->where('branch_id', Auth::user()->branch_id)->whereBetween("sale_date", [$request->datefrom, $request->dateto])
                ->where('customer_id', $request->customer)->orderBy("created_at", "desc")->get();
            }
        }
        return SaleResource::collection($debtors);
    }

    public function imported_debtors(){
        if(Auth::user()->branch_id==0){
            $debtors = Debtor::whereRaw('total > paid')->where('business_id', Auth::user()->business_id)->orderBy("created_at", "desc")->get();
        }
        else{
            $debtors = Debtor::whereRaw('total > paid')->where('branch_id', Auth::user()->branch_id)->orderBy("created_at", "desc")->get();
        }
        return DebtorResource::collection($debtors);
    }

    public function clearImportedDebt(Request $request,$id){
        $request->validate([
            'paid' => 'required',
            'mode' => 'required',
        ]);
        // generate receipt
        $receiptdet = $this->functionmaster->generateReceipt();
        $recno = $receiptdet['recno'];
        $receipt = $receiptdet['receipt'];



        // return $request;
        $debt = Debtor::find($id);
        $setting = GeneralSetting::where('branch_id', Auth::user()->branch_id)->first();

        $setting = GeneralSetting::where('branch_id', Auth::user()->branch_id)->first();
        if($debt->total<($debt->paid + str_replace(",","",$request->paid)) && $setting->deposit_balances == 1){
            $deposit = ($debt->paid + str_replace(",","",$request->paid))-$debt->total;

            $cdeposit=CustomerDeposit::create([
                'customer_id' => $debt->customer_id,
                'amount' => round($deposit, 2),
                'date' => $request->date ?? date('Y-m-d'),
                'mode' => $request->mode,
                'user_id' => Auth::user()->id,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
            ]);

            $spayment = Payment::create([
                'sale_id' => $debt->id,
                'amount' => str_replace(",","",$request->paid),
                'mode' => $request->mode,
                'receipt' => $receipt,
                'date' => $request->date ?? date('Y-m-d'),
                'recno' => $recno,
                'status' => 2,
                'month' => date("m"),
                'year' => date("Y"),
                'user_id' => Auth::user()->id,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
            ]);

           CustomerAccount::create([
               'customer_id' => $cdeposit->customer_id,
               'amount_in' => $cdeposit->amount,
               'amount_out' => 0,
               'category' => 2,
               'date' => $cdeposit->date,
               'mode' => $cdeposit->mode,
               'tId' => $cdeposit->id,
               'description' => 'Customer Deposit',
               'user_id' => Auth::user()->id,
               'branch_id' => Auth::user()->branch_id,
               'business_id' => Auth::user()->business_id,

           ]);

            app('App\Http\Controllers\FunctionController')->transaction(9,$cdeposit->id,$cdeposit->amount,0,$cdeposit->mode,"Recorded a customer Deposit",$cdeposit->date);

            $action = "Recorded a  Customer deposit of: ".$deposit." From ".Customer::find($cdeposit->customer_id)->name;
                app('App\Http\Controllers\FunctionController')->log($action,Log::Finance);

            $paid = $debt->total;
        }else{
            $paid = $debt->paid + str_replace(",","",$request->paid);

            $spayment = Payment::create([
                'sale_id' => $debt->id,
                'amount' => str_replace(",","",$request->paid),
                'mode' => $request->mode,
                'receipt' => $receipt,
                'date' => $request->date ?? date('Y-m-d'),
                'recno' => $recno,
                'status' => 2,
                'month' => date("m"),
                'year' => date("Y"),
                'user_id' => Auth::user()->id,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
            ]);

            CustomerAccount::create([
                'customer_id' => $debtor->customer_id,
                'amount_in' => $spayment->amount,
                'amount_out' => 0,
                'category' => 2,
                'date' => $spayment->date,
                'mode' => $spayment->id,
                'tId' => $spayment->id,
                'description' => 'Cleared Imported Debtor',
                'user_id' => Auth::user()->id,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,

            ]);

        }

        $debt->paid = $paid;
        $debt->save();


        // insert into transactions
        $this->functionmaster->transaction(1,$spayment->id,$spayment->amount,0,$request->mode,"Debt Clearance",$spayment->date);
        // insert into logs
        $this->functionmaster->log("Cleared a previous debt of amount $request->paid", Log::Sale);

        return response()->json(["msg" => "Previous debt payment has been successfully cleared"], 200);
    }


    // Fetch Client Payments
    public function payments(Request $request){
        if(Auth::user()->branch_id==0){
            if($request->customer != ""){
                $payments = Payment::where('business_id', Auth::user()->business_id)->whereBetween("date", [$request->datefrom, $request->dateto])->whereHas('sale', function ($query) use($request) {
                    return $query ->where("customer_id", $request->customer);
                })->with('sale')->orderBy("created_at", "desc")->get();
            }else{
                $payments = Payment::where('business_id', Auth::user()->business_id)->whereBetween("date", [$request->datefrom, $request->dateto])->with('sale')->orderBy("created_at", "desc")->get();
            }
        }
        else{
            if($request->customer != ""){
                $payments = Payment::where('branch_id', Auth::user()->branch_id)->whereBetween("date", [$request->datefrom, $request->dateto])->whereHas('sale', function ($query) use($request) {
                    return $query ->where("customer_id", $request->customer);
                })->with('sale')->orderBy("created_at", "desc")->get();
            }else{
                $payments = Payment::where('branch_id', Auth::user()->branch_id)->whereBetween("date", [$request->datefrom, $request->dateto])->with('sale')->orderBy("created_at", "desc")->get();
            }
        }
        return PaymentResource::collection($payments);
    }


    public function clearDebt(Request $request,$id){
        $request->validate([
            'paid' => 'required',
            'mode' => 'required',
        ]);
        if(str_replace(",","",$request->paid) <= 0){
            return response(['message'=>"Amount Paid Must Be greater than 0"],201);
        }
        // generate receipt
        $receiptdet = $this->functionmaster->generateReceipt();
        $recno = $receiptdet['recno'];
        $receipt = $receiptdet['receipt'];

        // return $request;
        $sale = Sale::find($id);

        // $olddata = Payment::where('sale_id', $id)->where('amount', str_replace(",","",$request->paid))->where('receipt', $receipt-1)
        // ->where('branch_id', Auth::user()->branch_id)->where('user_id', Auth::user()->id)->first();

        // if($olddata){
        //     return response(['message'=>"This payment has already been recorded"],200);
        // }
        if($sale->total<=$sale->paid){
            return response(['message'=>"All Balances for This Sale are already cleared"],200);
        }
        $setting = GeneralSetting::where('branch_id', Auth::user()->branch_id)->first();
        if((str_replace(",","",$request->paid)+$sale->paid)>$sale->total && $setting->deposit_balances == 1){
            $paid = $sale->total-$sale->paid;
            $deposit = (str_replace(",","",$request->paid)+$sale->paid)-$sale->total;

            $cdeposit=CustomerDeposit::create([
                'customer_id' => $sale->customer_id,
                'amount' => round($deposit, 2),
                'date' => $request->date ?? date('Y-m-d'),
                'mode' => $request->mode,
                'user_id' => Auth::user()->id,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
            ]);

            CustomerAccount::create([
               'customer_id' => $cdeposit->customer_id,
               'amount_in' => $cdeposit->amount,
               'amount_out' => 0,
               'category' => 2,
               'date' => $cdeposit->date,
               'mode' => $cdeposit->mode,
               'tId' => $cdeposit->id,
               'description' => 'Customer Deposit',
               'user_id' => Auth::user()->id,
               'branch_id' => Auth::user()->branch_id,
               'business_id' => Auth::user()->business_id,

           ]);

           $this->functionmaster->transaction(9,$cdeposit->id,$cdeposit->amount,0,$cdeposit->mode,"Recorded a customer Deposit",$cdeposit->date);

            $action = "Recorded a  Customer deposit of: ".$deposit." From ".Customer::find($cdeposit->customer_id)->name;
            $this->functionmaster->log($action,Log::Finance);

        }else{
            $paid = str_replace(",","",$request->paid);
        }

        $sale->paid = $sale->paid + $paid;
        $sale->save();

        $spayment = Payment::create([
            'sale_id' => $id,
            'amount' => $paid,
            'mode' => $request->mode,
            'type' => 1,
            'receipt' => $receipt,
            'date' => $request->date ?? date('Y-m-d'),
            'recno' => $recno,
            'month' => date("m"),
            'year' => date("Y"),
            'user_id' => Auth::user()->id,
            'branch_id' => Auth::user()->branch_id,
            'business_id' => Auth::user()->business_id,
        ]);

        // insert into customer account
        CustomerAccount::create([
            'customer_id' => $sale->customer_id,
            'amount_in' => $paid,
            'amount_out' => 0,
            'tId' => $spayment->id,
            'mode' => $spayment->mode,
            'date' => $spayment->date,
            'description' => "Clearing balance from buying items of ".$sale->total,
            'user_id' => $sale->user_id,
            'branch_id' => $sale->branch_id,
            'business_id' => $sale->business_id,
        ]);
        $setting = GeneralSetting::where('branch_id', Auth::user()->branch_id)->first();
        if($sale->customer_id && $setting->message_saledetails == 1){
            $salemessage = 'Your payment of '.$paid.' has been received and you have a pending balance of '.$sale->total-$sale->paid.'. Thank you for supporting '.Business::find(Auth::user()->business_id)->name;
            $smsSize = ceil(strlen($salemessage) / 160);
            $totalSMS = intVal($smsSize);
            //fetch sms balance
            $smsBalance = SmsBalance::where("business_id", Auth::user()->business_id)->where("branch_id", Auth::user()->branch_id)->first()->amount ?? 0;
            if ($smsBalance >= $totalSMS) {
                $reciepients = $request->recievers;
                //sending the sms
                $contact = substr(Customer::find($sale->customer_id)->contact, -9);
                //using the message api function
                $this->sendSMSAPI($contact, $salemessage, $smsSize, 'Sales');
            } else {
                return response()->json(["msg" => "You Have Few SMS, " . $totalSMS . ": SMS Needed, " . $smsBalance . ": SMS Remaining. Please request for more SMS", "status" => 500]);
            }
        }

        // insert into transactions
        $this->functionmaster->transaction(1,$spayment->id,$spayment->amount,0,$request->mode,"Debt Clearance",$spayment->date);
        // insert into logs
        $this->functionmaster->log("Cleared a debt of amount $request->paid", Log::Sale);

        return response(["id" => $id, "msg" => "Debt payment has been successfully recorded", 'status'=> 200]);
    }

    public function untakenOrders($sale){
        $debtors = SaleDetail::where('quantity_remaining',"!=",'0')->where('branch_id', Auth::user()->branch_id)
        ->where('sale_id',$sale)->get();
        return SaleDetailResource::collection($debtors);
    }

    public function updateQtyTaken(Request $request, $id){
        $request->validate([
            'id' => 'required',
            'quantity' => 'required'
        ]);
        $saledetail = SaleDetail::find($id);
        $saledetail->quantity_remaining = $saledetail->quantity_remaining-$request->quantity;
        $saledetail->save();
    }
}
