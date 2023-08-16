<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PaymentOption;
use App\Models\Permission;
use App\Models\Sale;
use App\Models\Stock;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function summary(Request $request)
    {
        $msales=[];
        $mpurchases=[];
        $mexpenses=[];
        $year=date('Y');
        $today=date('Y-m-d');
        $permission = Permission::where('id', Auth::user()->role)->first('view_user_summary');
        if(Auth::user()->branch_id == 0){
            if($request->branch_id != '0'){
                $buyingstockvalue=Stock::where('branch_id', $request->branch_id)->whereRelation('product','is_product',1)->whereHas('product', function ($query) {
                    return $query ->where('status', 1);
                })->sum(DB::raw('quantity * buying_price'));
                $balance=PaymentOption::where('branch_id', $request->branch_id)->sum('balance');
                $sellingstockvalue=Product::where('branch_id', $request->branch_id)->where('is_product',1)->where('status',1)->sum(DB::raw('quantity * selling_price'));
                $totalDebts = Sale::where('branch_id', $request->branch_id)->sum(DB::raw('total - paid'));
                $instock=Product::where('branch_id', $request->branch_id)->where('quantity','>',0)->where('status',1)->where('is_product',1)->count();
                $outstock=Product::where('branch_id', $request->branch_id)->where('quantity','<=',0)->where('status',1)->where('is_product',1)->count();
                $creditors = Purchase::where('branch_id', $request->branch_id)->sum(DB::raw('ROUND(total - paid, 2)'));
                $totalSales = Sale::where('branch_id', $request->branch_id)->whereYear('sale_date',$year)->sum('total');
                $totalPurchases = Purchase::where('branch_id', $request->branch_id)->whereYear('date',$year)->sum('total');
                $totalExpenses=Expense::where('branch_id', $request->branch_id)->whereYear('date',$year)->sum('amount');
                for ($i=1; $i <=12; $i++) {
                    if($i<10){
                    $i='0'.$i;
                    }
                    $msale = Sale::where('branch_id', $request->branch_id)->whereYear('sale_date',$year)->whereMonth('sale_date',$i)->sum('total');
                    $mpurchase = Purchase::where('branch_id', $request->branch_id)->whereYear('date',$year)->whereMonth('date',$i)->sum('total');
                    $mexpense=Expense::where('branch_id', $request->branch_id)->whereYear('date',$year)->whereMonth('date',$i)->sum('amount');

                    array_push($msales,$msale);
                    array_push($mpurchases,$mpurchase);
                    array_push($mexpenses,$mexpense);
                }
                if($request->time == 'today'){
                    $day=date('Y-m-d');
                    $sales = Sale::where('branch_id', $request->branch_id)->where('sale_date',$day)->sum('total');
                    $paid = Sale::where('branch_id', $request->branch_id)->where('sale_date',$day)->sum('paid');
                    $creditsPaid=Payment::where('branch_id', $request->branch_id)->where('date',$day)
                    ->whereRelation('sale','sale_date','!=',$today)->sum('amount');
                    $expenses=Expense::where('branch_id', $request->branch_id)->where('date',$day)->sum('amount');
                    $arr=[
                        'currency_code'=>Auth::user()->business->currency->currency_code??'',
                        'sales'=>$sales,
                        'debts'=> round($sales-$paid, 2),
                        'credit'=>round($creditsPaid, 2),
                        'expenses'=>$expenses,
                        'cash'=>round(($paid+$creditsPaid)-$expenses, 2),
                        'BuyingstockValue'=>round($buyingstockvalue,2),
                        'SellingstockValue'=>round($sellingstockvalue,2),
                        'Buying_businessvalue' =>round(($buyingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                        'Selling_businessvalue' =>round(($sellingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                        'instock' =>$instock,
                        'outstock' =>$outstock,
                        'creditors'=>round($creditors, 2),
                        'totalSales'=>$totalSales,
                        'totalPurchases'=>round($totalPurchases, 2),
                        'totalExpenses'=>$totalExpenses,
                        'msales'=>$msales,
                        'mpurchases'=>$mpurchases,
                        'mexpenses'=>$mexpenses,
                        'businessname'=>Auth::user()->business->name,
                        'branchname'=>Auth::user()->branch?Auth::user()->branch->name:'',
                        'branchid'=>Auth::user()->branch_id,
                    ];
                }elseif($request->time == 'yesterday'){
                    $day = Carbon::yesterday();
                    $sales = Sale::where('branch_id', $request->branch_id)->where('sale_date',$day)->sum('total');
                    $paid = Sale::where('branch_id', $request->branch_id)->where('sale_date',$day)->sum('paid');
                    $creditsPaid=Payment::where('branch_id', $request->branch_id)->where('date',$day)
                    ->whereRelation('sale','sale_date','!=',$today)->sum('amount');
                    $expenses=Expense::where('branch_id', $request->branch_id)->where('date',$day)->sum('amount');
                    $arr=[
                        'currency_code'=>Auth::user()->business->currency->currency_code??'',
                        'sales'=>$sales,
                        'debts'=> round($sales-$paid, 2),
                        'credit'=>round($creditsPaid, 2),
                        'expenses'=>$expenses,
                        'cash'=>round(($paid+$creditsPaid)-$expenses, 2),
                        'BuyingstockValue'=>round($buyingstockvalue,2),
                        'SellingstockValue'=>round($sellingstockvalue,2),
                        'Buying_businessvalue' =>round(($buyingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                        'Selling_businessvalue' =>round(($sellingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                        'instock' =>$instock,
                        'outstock' =>$outstock,
                        'creditors'=>round($creditors, 2),
                        'totalSales'=>$totalSales,
                        'totalPurchases'=>round($totalPurchases, 2),
                        'totalExpenses'=>$totalExpenses,
                        'msales'=>$msales,
                        'mpurchases'=>$mpurchases,
                        'mexpenses'=>$mexpenses,
                        'businessname'=>Auth::user()->business->name,
                        'branchname'=>Auth::user()->branch?Auth::user()->branch->name:'',
                        'branchid'=>Auth::user()->branch_id,
                    ];
                }elseif($request->time == 'last7'){
                    $day = Carbon::now()->subDays(7);
                    $sales = Sale::where('branch_id', $request->branch_id)->where('sale_date', '>=', $day)->sum('total');
                    $paid = Sale::where('branch_id', $request->branch_id)->where('sale_date', '>=', $day)->sum('paid');
                    $creditsPaid=Payment::where('branch_id', $request->branch_id)->where('date', '>=', $day)
                    ->whereRelation('sale','sale_date','!=',$today)->sum('amount');
                    $expenses=Expense::where('branch_id', $request->branch_id)->where('date', '>=', $day)->sum('amount');
                    $arr=[
                        'currency_code'=>Auth::user()->business->currency->currency_code??'',
                        'sales'=>$sales,
                        'debts'=> round($sales-$paid, 2),
                        'credit'=>round($creditsPaid, 2),
                        'expenses'=>$expenses,
                        'cash'=>round(($paid+$creditsPaid)-$expenses, 2),
                        'BuyingstockValue'=>round($buyingstockvalue,2),
                        'SellingstockValue'=>round($sellingstockvalue,2),
                        'Buying_businessvalue' =>round(($buyingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                        'Selling_businessvalue' =>round(($sellingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                        'instock' =>$instock,
                        'outstock' =>$outstock,
                        'creditors'=>round($creditors, 2),
                        'totalSales'=>$totalSales,
                        'totalPurchases'=>round($totalPurchases, 2),
                        'totalExpenses'=>$totalExpenses,
                        'msales'=>$msales,
                        'mpurchases'=>$mpurchases,
                        'mexpenses'=>$mexpenses,
                        'businessname'=>Auth::user()->business->name,
                        'branchname'=>Auth::user()->branch?Auth::user()->branch->name:'',
                        'branchid'=>Auth::user()->branch_id,
                    ];
                }elseif($request->time == 'last14'){
                    $day = Carbon::now()->subDays(14);
                    $sales = Sale::where('branch_id', $request->branch_id)->where('sale_date', '>=', $day)->sum('total');
                    $paid = Sale::where('branch_id', $request->branch_id)->where('sale_date', '>=', $day)->sum('paid');
                    $creditsPaid=Payment::where('branch_id', $request->branch_id)->where('date', '>=', $day)
                    ->whereRelation('sale','sale_date','!=',$today)->sum('amount');
                    $expenses=Expense::where('branch_id', $request->branch_id)->where('date', '>=', $day)->sum('amount');
                    $arr=[
                        'currency_code'=>Auth::user()->business->currency->currency_code??'',
                        'sales'=>$sales,
                        'debts'=> round($sales-$paid, 2),
                        'credit'=>round($creditsPaid, 2),
                        'expenses'=>$expenses,
                        'cash'=>round(($paid+$creditsPaid)-$expenses, 2),
                        'BuyingstockValue'=>round($buyingstockvalue,2),
                        'SellingstockValue'=>round($sellingstockvalue,2),
                        'Buying_businessvalue' =>round(($buyingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                        'Selling_businessvalue' =>round(($sellingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                        'instock' =>$instock,
                        'outstock' =>$outstock,
                        'creditors'=>round($creditors, 2),
                        'totalSales'=>$totalSales,
                        'totalPurchases'=>round($totalPurchases, 2),
                        'totalExpenses'=>$totalExpenses,
                        'msales'=>$msales,
                        'mpurchases'=>$mpurchases,
                        'mexpenses'=>$mexpenses,
                        'businessname'=>Auth::user()->business->name,
                        'branchname'=>Auth::user()->branch?Auth::user()->branch->name:'',
                        'branchid'=>Auth::user()->branch_id,
                    ];
                }elseif($request->time == 'last28'){
                    $day = Carbon::now()->subDays(28);
                    $sales = Sale::where('branch_id', $request->branch_id)->where('sale_date', '>=', $day)->sum('total');
                    $paid = Sale::where('branch_id', $request->branch_id)->where('sale_date', '>=', $day)->sum('paid');
                    $creditsPaid=Payment::where('branch_id', $request->branch_id)->where('date', '>=', $day)
                    ->whereRelation('sale','sale_date','!=',$today)->sum('amount');
                    $expenses=Expense::where('branch_id', $request->branch_id)->where('date', '>=', $day)->sum('amount');
                    $arr=[
                        'currency_code'=>Auth::user()->business->currency->currency_code??'',
                        'sales'=>$sales,
                        'debts'=> round($sales-$paid, 2),
                        'credit'=>round($creditsPaid, 2),
                        'expenses'=>$expenses,
                        'cash'=>round(($paid+$creditsPaid)-$expenses, 2),
                        'BuyingstockValue'=>round($buyingstockvalue,2),
                        'SellingstockValue'=>round($sellingstockvalue,2),
                        'Buying_businessvalue' =>round(($buyingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                        'Selling_businessvalue' =>round(($sellingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                        'instock' =>$instock,
                        'outstock' =>$outstock,
                        'creditors'=>round($creditors, 2),
                        'totalSales'=>$totalSales,
                        'totalPurchases'=>round($totalPurchases, 2),
                        'totalExpenses'=>$totalExpenses,
                        'msales'=>$msales,
                        'mpurchases'=>$mpurchases,
                        'mexpenses'=>$mexpenses,
                        'businessname'=>Auth::user()->business->name,
                        'branchname'=>Auth::user()->branch?Auth::user()->branch->name:'',
                        'branchid'=>Auth::user()->branch_id,
                    ];
                }elseif($request->time == 'month'){
                    $day = Carbon::now()->startOfMonth();
                    $sales = Sale::where('branch_id', $request->branch_id)->where('sale_date', '>=', $day)->sum('total');
                    $paid = Sale::where('branch_id', $request->branch_id)->where('sale_date', '>=', $day)->sum('paid');
                    $creditsPaid=Payment::where('branch_id', $request->branch_id)->where('date', '>=', $day)
                    ->whereRelation('sale','sale_date','!=',$today)->sum('amount');
                    $expenses=Expense::where('branch_id', $request->branch_id)->where('date', '>=', $day)->sum('amount');
                    $arr=[
                        'currency_code'=>Auth::user()->business->currency->currency_code??'',
                        'sales'=>$sales,
                        'debts'=> round($sales-$paid, 2),
                        'credit'=>round($creditsPaid, 2),
                        'expenses'=>$expenses,
                        'cash'=>round(($paid+$creditsPaid)-$expenses, 2),
                        'BuyingstockValue'=>round($buyingstockvalue,2),
                        'SellingstockValue'=>round($sellingstockvalue,2),
                        'Buying_businessvalue' =>round(($buyingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                        'Selling_businessvalue' =>round(($sellingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                        'instock' =>$instock,
                        'outstock' =>$outstock,
                        'creditors'=>round($creditors, 2),
                        'totalSales'=>$totalSales,
                        'totalPurchases'=>round($totalPurchases, 2),
                        'totalExpenses'=>$totalExpenses,
                        'msales'=>$msales,
                        'mpurchases'=>$mpurchases,
                        'mexpenses'=>$mexpenses,
                        'businessname'=>Auth::user()->business->name,
                        'branchname'=>Auth::user()->branch?Auth::user()->branch->name:'',
                        'branchid'=>Auth::user()->branch_id,
                    ];
                }elseif($request->time == 'quarter'){
                    $day = Carbon::now()->startOfQuarter();
                    $sales = Sale::where('branch_id', $request->branch_id)->where('sale_date', '>=', $day)->sum('total');
                    $paid = Sale::where('branch_id', $request->branch_id)->where('sale_date', '>=', $day)->sum('paid');
                    $creditsPaid=Payment::where('branch_id', $request->branch_id)->where('date', '>=', $day)
                    ->whereRelation('sale','sale_date','!=',$today)->sum('amount');
                    $expenses=Expense::where('branch_id', $request->branch_id)->where('date', '>=', $day)->sum('amount');
                    $arr=[
                        'currency_code'=>Auth::user()->business->currency->currency_code??'',
                        'sales'=>$sales,
                        'debts'=> round($sales-$paid, 2),
                        'credit'=>round($creditsPaid, 2),
                        'expenses'=>$expenses,
                        'cash'=>round(($paid+$creditsPaid)-$expenses, 2),
                        'BuyingstockValue'=>round($buyingstockvalue,2),
                        'SellingstockValue'=>round($sellingstockvalue,2),
                        'Buying_businessvalue' =>round(($buyingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                        'Selling_businessvalue' =>round(($sellingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                        'instock' =>$instock,
                        'outstock' =>$outstock,
                        'creditors'=>round($creditors, 2),
                        'totalSales'=>$totalSales,
                        'totalPurchases'=>round($totalPurchases, 2),
                        'totalExpenses'=>$totalExpenses,
                        'msales'=>$msales,
                        'mpurchases'=>$mpurchases,
                        'mexpenses'=>$mexpenses,
                        'businessname'=>Auth::user()->business->name,
                        'branchname'=>Auth::user()->branch?Auth::user()->branch->name:'',
                        'branchid'=>Auth::user()->branch_id,
                    ];
                }elseif($request->time == 'custom'){
                    $from = $request->dateRange[0] ?? Carbon::now()->startOfMonth();
                    $to = $request->dateRange[1] ?? Carbon::now();
                    $sales = Sale::where('branch_id', $request->branch_id)->whereBetween('sale_date', [$from, $to])->sum('total');
                    $paid = Sale::where('branch_id', $request->branch_id)->whereBetween('sale_date', [$from, $to])->sum('paid');
                    $creditsPaid=Payment::where('branch_id', $request->branch_id)->whereBetween('date', [$from, $to])
                    ->whereRelation('sale','sale_date','!=',$today)->sum('amount');
                    $expenses=Expense::where('branch_id', $request->branch_id)->whereBetween('date', [$from, $to])->sum('amount');
                    $arr=[
                        'currency_code'=>Auth::user()->business->currency->currency_code??'',
                        'sales'=>$sales,
                        'debts'=> round($sales-$paid, 2),
                        'credit'=>round($creditsPaid, 2),
                        'expenses'=>$expenses,
                        'cash'=>round(($paid+$creditsPaid)-$expenses, 2),
                        'BuyingstockValue'=>round($buyingstockvalue,2),
                        'SellingstockValue'=>round($sellingstockvalue,2),
                        'Buying_businessvalue' =>round(($buyingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                        'Selling_businessvalue' =>round(($sellingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                        'instock' =>$instock,
                        'outstock' =>$outstock,
                        'creditors'=>round($creditors, 2),
                        'totalSales'=>$totalSales,
                        'totalPurchases'=>round($totalPurchases, 2),
                        'totalExpenses'=>$totalExpenses,
                        'msales'=>$msales,
                        'mpurchases'=>$mpurchases,
                        'mexpenses'=>$mexpenses,
                        'businessname'=>Auth::user()->business->name,
                        'branchname'=>Auth::user()->branch?Auth::user()->branch->name:'',
                        'branchid'=>Auth::user()->branch_id,
                    ];
                }
            }else{
                if($request->time == 'today'){
                    $day=date('Y-m-d');
                    $sales = Sale::where('business_id', Auth::user()->business_id)->where('sale_date',$day)->sum('total');
                    $paid = Sale::where('business_id', Auth::user()->business_id)->where('sale_date',$day)->sum('paid');
                    $creditsPaid=Payment::where('business_id', Auth::user()->business_id)->where('date',$day)
                    ->whereRelation('sale','sale_date','!=',$day)->sum('amount');
                    $expenses=Expense::where('business_id', Auth::user()->business_id)->where('date',$day)->sum('amount');
                }elseif($request->time == 'yesterday'){
                    $day = Carbon::yesterday();
                    $sales = Sale::where('business_id', Auth::user()->business_id)->where('sale_date',$day)->sum('total');
                    $paid = Sale::where('business_id', Auth::user()->business_id)->where('sale_date',$day)->sum('paid');
                    $creditsPaid=Payment::where('business_id', Auth::user()->business_id)->where('date',$day)
                    ->whereRelation('sale','sale_date','!=',$day)->sum('amount');
                    $expenses=Expense::where('business_id', Auth::user()->business_id)->where('date',$day)->sum('amount');
                }elseif($request->time == 'last7'){
                    $day = Carbon::now()->subDays(7);
                    $sales = Sale::where('business_id', Auth::user()->business_id)->where('sale_date', '>=', $day)->sum('total');
                    $paid = Sale::where('business_id', Auth::user()->business_id)->where('sale_date', '>=', $day)->sum('paid');
                    $creditsPaid=Payment::where('business_id', Auth::user()->business_id)->where('date', '>=', $day)
                    ->whereRelation('sale','sale_date','!=',$day)->sum('amount');
                    $expenses=Expense::where('business_id', Auth::user()->business_id)->where('date', '>=', $day)->sum('amount');
                }elseif($request->time == 'last14'){
                    $day = Carbon::now()->subDays(14);
                    $sales = Sale::where('business_id', Auth::user()->business_id)->where('sale_date', '>=', $day)->sum('total');
                    $paid = Sale::where('business_id', Auth::user()->business_id)->where('sale_date', '>=', $day)->sum('paid');
                    $creditsPaid=Payment::where('business_id', Auth::user()->business_id)->where('date', '>=', $day)
                    ->whereRelation('sale','sale_date','!=',$day)->sum('amount');
                    $expenses=Expense::where('business_id', Auth::user()->business_id)->where('date', '>=', $day)->sum('amount');
                }elseif($request->time == 'last28'){
                    $day = Carbon::now()->subDays(28);
                    $sales = Sale::where('business_id', Auth::user()->business_id)->where('sale_date', '>=', $day)->sum('total');
                    $paid = Sale::where('business_id', Auth::user()->business_id)->where('sale_date', '>=', $day)->sum('paid');
                    $creditsPaid=Payment::where('business_id', Auth::user()->business_id)->where('date', '>=', $day)
                    ->whereRelation('sale','sale_date','!=',$day)->sum('amount');
                    $expenses=Expense::where('business_id', Auth::user()->business_id)->where('date', '>=', $day)->sum('amount');
                }elseif($request->time == 'month'){
                    $day = Carbon::now()->startOfMonth();
                    $sales = Sale::where('business_id', Auth::user()->business_id)->where('sale_date', '>=', $day)->sum('total');
                    $paid = Sale::where('business_id', Auth::user()->business_id)->where('sale_date', '>=', $day)->sum('paid');
                    $creditsPaid=Payment::where('business_id', Auth::user()->business_id)->where('date', '>=', $day)
                    ->whereRelation('sale','sale_date','!=',$day)->sum('amount');
                    $expenses=Expense::where('business_id', Auth::user()->business_id)->where('date', '>=', $day)->sum('amount');
                }elseif($request->time == 'quarter'){
                    $day = Carbon::now()->startOfQuarter();
                    $sales = Sale::where('business_id', Auth::user()->business_id)->where('sale_date', '>=', $day)->sum('total');
                    $paid = Sale::where('business_id', Auth::user()->business_id)->where('sale_date', '>=', $day)->sum('paid');
                    $creditsPaid=Payment::where('business_id', Auth::user()->business_id)->where('date', '>=', $day)
                    ->whereRelation('sale','sale_date','!=',$day)->sum('amount');
                    $expenses=Expense::where('business_id', Auth::user()->business_id)->where('date', '>=', $day)->sum('amount');
                }elseif($request->time == 'custom'){
                    $from = $request->dateRange[0] ?? Carbon::now()->startOfMonth();
                    $to = $request->dateRange[1] ?? Carbon::now();
                    $sales = Sale::where('business_id', Auth::user()->business_id)->whereBetween('sale_date', [$from, $to])->sum('total');
                    $paid = Sale::where('business_id', Auth::user()->business_id)->whereBetween('sale_date', [$from, $to])->sum('paid');
                    $creditsPaid=Payment::where('business_id', Auth::user()->business_id)->whereBetween('date', [$from, $to])
                    ->whereRelation('sale','sale_date','!=',$day)->sum('amount');
                    $expenses=Expense::where('business_id', Auth::user()->business_id)->whereBetween('date', [$from, $to])->sum('amount');
                }
                $sales = Sale::where('business_id', Auth::user()->business_id)->where('sale_date',$day)->sum('total');
                $paid = Sale::where('business_id', Auth::user()->business_id)->where('sale_date',$day)->sum('paid');
                $creditsPaid=Payment::where('business_id', Auth::user()->business_id)->where('date',$day)
                ->whereRelation('sale','sale_date','!=',$day)->sum('amount');
                $expenses=Expense::where('business_id', Auth::user()->business_id)->where('date',$day)->sum('amount');
                $buyingstockvalue=Stock::where('business_id', Auth::user()->business_id)->whereRelation('product','is_product',1)->whereHas('product', function ($query) {
                    return $query ->where('status', 1);
                })->sum(DB::raw('quantity * buying_price'));
                $balance=PaymentOption::where('business_id', Auth::user()->business_id)->sum('balance');
                $totalDebts = Sale::where('business_id', Auth::user()->business_id)->sum(DB::raw('total - paid'));
                $sellingstockvalue=Product::where('business_id', Auth::user()->business_id)->where('is_product',1)->sum(DB::raw('quantity * selling_price'));
                $instock=Product::where('business_id', Auth::user()->business_id)->where('quantity','>',0)->where('is_product',1)->count();
                $outstock=Product::where('business_id', Auth::user()->business_id)->where('quantity','<=',0)->where('is_product',1)->count();
                $creditors = Purchase::where('business_id', Auth::user()->business_id)->sum(DB::raw('ROUND(total - paid, 2)'));
                $totalSales = Sale::where('business_id', Auth::user()->business_id)->whereYear('sale_date',$year)->sum('total');
                $totalPurchases = Purchase::where('business_id', Auth::user()->business_id)->whereYear('date',$year)->sum('total');
                $totalExpenses=Expense::where('business_id', Auth::user()->business_id)->whereYear('date',$year)->sum('amount');
                for ($i=1; $i <=12; $i++) {
                    if($i<10){
                    $i='0'.$i;
                    }
                    $msale = Sale::where('business_id', Auth::user()->business_id)->whereYear('sale_date',$year)->whereMonth('sale_date',$i)->sum('total');
                    $mpurchase = Purchase::where('business_id', Auth::user()->business_id)->whereYear('date',$year)->whereMonth('date',$i)->sum('total');
                    $mexpense=Expense::where('business_id', Auth::user()->business_id)->whereYear('date',$year)->sum('amount');

                    array_push($msales,$msale);
                    array_push($mpurchases,$mpurchase);
                    array_push($mexpenses,$mexpense);
                }
                $arr=[
                    'currency_code'=>Auth::user()->business->currency->currency_code??'',
                    'sales'=>$sales,
                    'debts'=> round($sales-$paid, 2),
                    'credit'=>round($creditsPaid, 2),
                    'expenses'=>$expenses,
                    'cash'=>round(($paid+$creditsPaid)-$expenses, 2),
                    'BuyingstockValue'=>round($buyingstockvalue,2),
                    'SellingstockValue'=>round($sellingstockvalue,2),
                    'Buying_businessvalue' =>round(($buyingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                    'Selling_businessvalue' =>round(($sellingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                    'instock' =>$instock,
                    'outstock' =>$outstock,
                    'creditors'=>round($creditors, 2),
                    'totalSales'=>$totalSales,
                    'totalPurchases'=>round($totalPurchases, 2),
                    'totalExpenses'=>$totalExpenses,
                    'msales'=>$msales,
                    'mpurchases'=>$mpurchases,
                    'mexpenses'=>$mexpenses,
                    'businessname'=>Auth::user()->business->name,
                    'branchname'=>Auth::user()->branch?Auth::user()->branch->name:'',
                    'branchid'=>Auth::user()->branch_id,

                ];
            }
        }else{
            $buyingstockvalue=Stock::where('branch_id', Auth::user()->branch_id)->whereRelation('product','is_product',1)->whereHas('product', function ($query) {
                return $query ->where('status', 1);
            })->sum(DB::raw('quantity * buying_price'));
            $balance=PaymentOption::where('branch_id', Auth::user()->branch_id)->sum('balance');
            $sellingstockvalue=Product::where('branch_id', Auth::user()->branch_id)->where('is_product',1)->where('status',1)->sum(DB::raw('quantity * selling_price'));
            $totalDebts = Sale::where('branch_id', Auth::user()->branch_id)->sum(DB::raw('total - paid'));
            $instock=Product::where('branch_id', Auth::user()->branch_id)->where('quantity','>',0)->where('status',1)->where('is_product',1)->count();
            $outstock=Product::where('branch_id', Auth::user()->branch_id)->where('quantity','<=',0)->where('status',1)->where('is_product',1)->count();
            $creditors = Purchase::where('branch_id', Auth::user()->branch_id)->sum(DB::raw('ROUND(total - paid, 2)'));
            $totalSales = Sale::where('branch_id', Auth::user()->branch_id)->whereYear('sale_date',$year)->sum('total');
            $totalPurchases = Purchase::where('branch_id', Auth::user()->branch_id)->whereYear('date',$year)->sum('total');
            $totalExpenses=Expense::where('branch_id', Auth::user()->branch_id)->whereYear('date',$year)->sum('amount');
            for ($i=1; $i <=12; $i++) {
                if($i<10){
                $i='0'.$i;
                }
                $msale = Sale::where('branch_id', Auth::user()->branch_id)->whereYear('sale_date',$year)->whereMonth('sale_date',$i)->sum('total');
                $mpurchase = Purchase::where('branch_id', Auth::user()->branch_id)->whereYear('date',$year)->whereMonth('date',$i)->sum('total');
                $mexpense=Expense::where('branch_id', Auth::user()->branch_id)->whereYear('date',$year)->whereMonth('date',$i)->sum('amount');

                array_push($msales,$msale);
                array_push($mpurchases,$mpurchase);
                array_push($mexpenses,$mexpense);
            }
            if($request->time == 'today'){
                $day=date('Y-m-d');
                $sales = Sale::where('branch_id', Auth::user()->branch_id)->where('sale_date',$day)->sum('total');
                $paid = Sale::where('branch_id', Auth::user()->branch_id)->where('sale_date',$day)->sum('paid');
                $creditsPaid=Payment::where('branch_id', Auth::user()->branch_id)->where('date',$day)
                ->whereRelation('sale','sale_date','!=',$today)->sum('amount');
                $expenses=Expense::where('branch_id', Auth::user()->branch_id)->where('date',$day)->sum('amount');
                $arr=[
                    'currency_code'=>Auth::user()->business->currency->currency_code??'',
                    'sales'=>$sales,
                    'debts'=> round($sales-$paid, 2),
                    'credit'=>round($creditsPaid, 2),
                    'expenses'=>$expenses,
                    'cash'=>round(($paid+$creditsPaid)-$expenses, 2),
                    'BuyingstockValue'=>round($buyingstockvalue,2),
                    'SellingstockValue'=>round($sellingstockvalue,2),
                    'Buying_businessvalue' =>round(($buyingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                    'Selling_businessvalue' =>round(($sellingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                    'instock' =>$instock,
                    'outstock' =>$outstock,
                    'creditors'=>round($creditors, 2),
                    'totalSales'=>$totalSales,
                    'totalPurchases'=>round($totalPurchases, 2),
                    'totalExpenses'=>$totalExpenses,
                    'msales'=>$msales,
                    'mpurchases'=>$mpurchases,
                    'mexpenses'=>$mexpenses,
                    'businessname'=>Auth::user()->business->name,
                    'branchname'=>Auth::user()->branch?Auth::user()->branch->name:'',
                    'branchid'=>Auth::user()->branch_id,
                ];
            }elseif($request->time == 'yesterday'){
                $day = Carbon::yesterday();
                $sales = Sale::where('branch_id', Auth::user()->branch_id)->where('sale_date',$day)->sum('total');
                $paid = Sale::where('branch_id', Auth::user()->branch_id)->where('sale_date',$day)->sum('paid');
                $creditsPaid=Payment::where('branch_id', Auth::user()->branch_id)->where('date',$day)
                ->whereRelation('sale','sale_date','!=',$day)->sum('amount');
                $expenses=Expense::where('branch_id', Auth::user()->branch_id)->where('date',$day)->sum('amount');
                $arr=[
                    'currency_code'=>Auth::user()->business->currency->currency_code??'',
                    'sales'=>$sales,
                    'debts'=> round($sales-$paid, 2),
                    'credit'=>round($creditsPaid, 2),
                    'expenses'=>$expenses,
                    'cash'=>round(($paid+$creditsPaid)-$expenses, 2),
                    'BuyingstockValue'=>round($buyingstockvalue,2),
                    'SellingstockValue'=>round($sellingstockvalue,2),
                    'Buying_businessvalue' =>round(($buyingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                    'Selling_businessvalue' =>round(($sellingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                    'instock' =>$instock,
                    'outstock' =>$outstock,
                    'creditors'=>round($creditors, 2),
                    'totalSales'=>$totalSales,
                    'totalPurchases'=>round($totalPurchases, 2),
                    'totalExpenses'=>$totalExpenses,
                    'msales'=>$msales,
                    'mpurchases'=>$mpurchases,
                    'mexpenses'=>$mexpenses,
                    'businessname'=>Auth::user()->business->name,
                    'branchname'=>Auth::user()->branch?Auth::user()->branch->name:'',
                    'branchid'=>Auth::user()->branch_id,
                ];
            }elseif($request->time == 'last7'){
                $day = Carbon::now()->subDays(7);
                $sales = Sale::where('branch_id', Auth::user()->branch_id)->where('sale_date', '>=', $day)->sum('total');
                $paid = Sale::where('branch_id', Auth::user()->branch_id)->where('sale_date', '>=', $day)->sum('paid');
                $creditsPaid=Payment::where('branch_id', Auth::user()->branch_id)->where('date', '>=', $day)
                ->whereRelation('sale','sale_date','!=',$today)->sum('amount');
                $expenses=Expense::where('branch_id', Auth::user()->branch_id)->where('date', '>=', $day)->sum('amount');
                $arr=[
                    'currency_code'=>Auth::user()->business->currency->currency_code??'',
                    'sales'=>$sales,
                    'debts'=> round($sales-$paid, 2),
                    'credit'=>round($creditsPaid, 2),
                    'expenses'=>$expenses,
                    'cash'=>round(($paid+$creditsPaid)-$expenses, 2),
                    'BuyingstockValue'=>round($buyingstockvalue,2),
                    'SellingstockValue'=>round($sellingstockvalue,2),
                    'Buying_businessvalue' =>round(($buyingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                    'Selling_businessvalue' =>round(($sellingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                    'instock' =>$instock,
                    'outstock' =>$outstock,
                    'creditors'=>round($creditors, 2),
                    'totalSales'=>$totalSales,
                    'totalPurchases'=>round($totalPurchases, 2),
                    'totalExpenses'=>$totalExpenses,
                    'msales'=>$msales,
                    'mpurchases'=>$mpurchases,
                    'mexpenses'=>$mexpenses,
                    'businessname'=>Auth::user()->business->name,
                    'branchname'=>Auth::user()->branch?Auth::user()->branch->name:'',
                    'branchid'=>Auth::user()->branch_id,
                ];
            }elseif($request->time == 'last14'){
                $day = Carbon::now()->subDays(14);
                $sales = Sale::where('branch_id', Auth::user()->branch_id)->where('sale_date', '>=', $day)->sum('total');
                $paid = Sale::where('branch_id', Auth::user()->branch_id)->where('sale_date', '>=', $day)->sum('paid');
                $creditsPaid=Payment::where('branch_id', Auth::user()->branch_id)->where('date', '>=', $day)
                ->whereRelation('sale','sale_date','!=',$today)->sum('amount');
                $expenses=Expense::where('branch_id', Auth::user()->branch_id)->where('date', '>=', $day)->sum('amount');
                $arr=[
                    'currency_code'=>Auth::user()->business->currency->currency_code??'',
                    'sales'=>$sales,
                    'debts'=> round($sales-$paid, 2),
                    'credit'=>round($creditsPaid, 2),
                    'expenses'=>$expenses,
                    'cash'=>round(($paid+$creditsPaid)-$expenses, 2),
                    'BuyingstockValue'=>round($buyingstockvalue,2),
                    'SellingstockValue'=>round($sellingstockvalue,2),
                    'Buying_businessvalue' =>round(($buyingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                    'Selling_businessvalue' =>round(($sellingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                    'instock' =>$instock,
                    'outstock' =>$outstock,
                    'creditors'=>round($creditors, 2),
                    'totalSales'=>$totalSales,
                    'totalPurchases'=>round($totalPurchases, 2),
                    'totalExpenses'=>$totalExpenses,
                    'msales'=>$msales,
                    'mpurchases'=>$mpurchases,
                    'mexpenses'=>$mexpenses,
                    'businessname'=>Auth::user()->business->name,
                    'branchname'=>Auth::user()->branch?Auth::user()->branch->name:'',
                    'branchid'=>Auth::user()->branch_id,
                ];
            }elseif($request->time == 'last28'){
                $day = Carbon::now()->subDays(28);
                $sales = Sale::where('branch_id', Auth::user()->branch_id)->where('sale_date', '>=', $day)->sum('total');
                $paid = Sale::where('branch_id', Auth::user()->branch_id)->where('sale_date', '>=', $day)->sum('paid');
                $creditsPaid=Payment::where('branch_id', Auth::user()->branch_id)->where('date', '>=', $day)
                ->whereRelation('sale','sale_date','!=',$today)->sum('amount');
                $expenses=Expense::where('branch_id', Auth::user()->branch_id)->where('date', '>=', $day)->sum('amount');
                $arr=[
                    'currency_code'=>Auth::user()->business->currency->currency_code??'',
                    'sales'=>$sales,
                    'debts'=> round($sales-$paid, 2),
                    'credit'=>round($creditsPaid, 2),
                    'expenses'=>$expenses,
                    'cash'=>round(($paid+$creditsPaid)-$expenses, 2),
                    'BuyingstockValue'=>round($buyingstockvalue,2),
                    'SellingstockValue'=>round($sellingstockvalue,2),
                    'Buying_businessvalue' =>round(($buyingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                    'Selling_businessvalue' =>round(($sellingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                    'instock' =>$instock,
                    'outstock' =>$outstock,
                    'creditors'=>round($creditors, 2),
                    'totalSales'=>$totalSales,
                    'totalPurchases'=>round($totalPurchases, 2),
                    'totalExpenses'=>$totalExpenses,
                    'msales'=>$msales,
                    'mpurchases'=>$mpurchases,
                    'mexpenses'=>$mexpenses,
                    'businessname'=>Auth::user()->business->name,
                    'branchname'=>Auth::user()->branch?Auth::user()->branch->name:'',
                    'branchid'=>Auth::user()->branch_id,
                ];
            }elseif($request->time == 'month'){
                $day = Carbon::now()->startOfMonth();
                $sales = Sale::where('branch_id', Auth::user()->branch_id)->where('sale_date', '>=', $day)->sum('total');
                $paid = Sale::where('branch_id', Auth::user()->branch_id)->where('sale_date', '>=', $day)->sum('paid');
                $creditsPaid=Payment::where('branch_id', Auth::user()->branch_id)->where('date', '>=', $day)
                ->whereRelation('sale','sale_date','!=',$today)->sum('amount');
                $expenses=Expense::where('branch_id', Auth::user()->branch_id)->where('date', '>=', $day)->sum('amount');
                $arr=[
                    'currency_code'=>Auth::user()->business->currency->currency_code??'',
                    'sales'=>$sales,
                    'debts'=> round($sales-$paid, 2),
                    'credit'=>round($creditsPaid, 2),
                    'expenses'=>$expenses,
                    'cash'=>round(($paid+$creditsPaid)-$expenses, 2),
                    'BuyingstockValue'=>round($buyingstockvalue,2),
                    'SellingstockValue'=>round($sellingstockvalue,2),
                    'Buying_businessvalue' =>round(($buyingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                    'Selling_businessvalue' =>round(($sellingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                    'instock' =>$instock,
                    'outstock' =>$outstock,
                    'creditors'=>round($creditors, 2),
                    'totalSales'=>$totalSales,
                    'totalPurchases'=>round($totalPurchases, 2),
                    'totalExpenses'=>$totalExpenses,
                    'msales'=>$msales,
                    'mpurchases'=>$mpurchases,
                    'mexpenses'=>$mexpenses,
                    'businessname'=>Auth::user()->business->name,
                    'branchname'=>Auth::user()->branch?Auth::user()->branch->name:'',
                    'branchid'=>Auth::user()->branch_id,
                ];
            }elseif($request->time == 'quarter'){
                $day = Carbon::now()->startOfQuarter();
                $sales = Sale::where('branch_id', Auth::user()->branch_id)->where('sale_date', '>=', $day)->sum('total');
                $paid = Sale::where('branch_id', Auth::user()->branch_id)->where('sale_date', '>=', $day)->sum('paid');
                $creditsPaid=Payment::where('branch_id', Auth::user()->branch_id)->where('date', '>=', $day)
                ->whereRelation('sale','sale_date','!=',$today)->sum('amount');
                $expenses=Expense::where('branch_id', Auth::user()->branch_id)->where('date', '>=', $day)->sum('amount');
                $arr=[
                    'currency_code'=>Auth::user()->business->currency->currency_code??'',
                    'sales'=>$sales,
                    'debts'=> round($sales-$paid, 2),
                    'credit'=>round($creditsPaid, 2),
                    'expenses'=>$expenses,
                    'cash'=>round(($paid+$creditsPaid)-$expenses, 2),
                    'BuyingstockValue'=>round($buyingstockvalue,2),
                    'SellingstockValue'=>round($sellingstockvalue,2),
                    'Buying_businessvalue' =>round(($buyingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                    'Selling_businessvalue' =>round(($sellingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                    'instock' =>$instock,
                    'outstock' =>$outstock,
                    'creditors'=>round($creditors, 2),
                    'totalSales'=>$totalSales,
                    'totalPurchases'=>round($totalPurchases, 2),
                    'totalExpenses'=>$totalExpenses,
                    'msales'=>$msales,
                    'mpurchases'=>$mpurchases,
                    'mexpenses'=>$mexpenses,
                    'businessname'=>Auth::user()->business->name,
                    'branchname'=>Auth::user()->branch?Auth::user()->branch->name:'',
                    'branchid'=>Auth::user()->branch_id,
                ];
            }elseif($request->time == 'custom'){
                $from = $request->dateRange[0] ?? Carbon::now()->startOfMonth();
                $to = $request->dateRange[1] ?? Carbon::now();
                $sales = Sale::where('branch_id', Auth::user()->branch_id)->whereBetween('sale_date', [$from, $to])->sum('total');
                $paid = Sale::where('branch_id', Auth::user()->branch_id)->whereBetween('sale_date', [$from, $to])->sum('paid');
                $creditsPaid=Payment::where('branch_id', Auth::user()->branch_id)->whereBetween('date', [$from, $to])
                ->whereRelation('sale','sale_date','!=',$today)->sum('amount');
                $expenses=Expense::where('branch_id', Auth::user()->branch_id)->whereBetween('date', [$from, $to])->sum('amount');
                $arr=[
                    'currency_code'=>Auth::user()->business->currency->currency_code??'',
                    'sales'=>$sales,
                    'debts'=> round($sales-$paid, 2),
                    'credit'=>round($creditsPaid, 2),
                    'expenses'=>$expenses,
                    'BuyingstockValue'=>round($buyingstockvalue,2),
                    'SellingstockValue'=>round($sellingstockvalue,2),
                    'Buying_businessvalue' =>round(($buyingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                    'Selling_businessvalue' =>round(($sellingstockvalue+$balance+$totalDebts)-$creditsPaid, 2),
                    'instock' =>$instock,
                    'outstock' =>$outstock,
                    'creditors'=>round($creditors, 2),
                    'totalSales'=>$totalSales,
                    'totalPurchases'=>round($totalPurchases, 2),
                    'totalExpenses'=>$totalExpenses,
                    'msales'=>$msales,
                    'mpurchases'=>$mpurchases,
                    'mexpenses'=>$mexpenses,
                    'businessname'=>Auth::user()->business->name,
                    'branchname'=>Auth::user()->branch?Auth::user()->branch->name:'',
                    'branchid'=>Auth::user()->branch_id,
                ];
            }
        }
        return $arr;
    }
}
