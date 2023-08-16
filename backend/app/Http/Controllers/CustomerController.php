<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerResource;
use App\Http\Resources\SaleResource;
use App\Http\Resources\CustomerAccountResource;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\CustomerAccount;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->branch_id==0){
            $customer = customer::where('business_id',Auth::user()->business_id)->where('status',1)->orderBy("created_at", "desc")->get();
        }
        else{
            $customer = customer::where('branch_id',Auth::user()->branch_id)->where('status',1)->orderBy("created_at", "desc")->get();
        }

        return customerResource::collection($customer);

    }
    public function balances()
    {

        $customers = CustomerAccount::with('customer')->where('branch_id',Auth::user()->branch_id)->distinct('customer_id')->orderBy("customer_id", "asc")->get(['customer_id']);
        $balances=[];
        foreach($customers as $customer){
            $in = CustomerAccount::where('branch_id',Auth::user()->branch_id)->where('customer_id',$customer->customer_id)->sum("amount_in");
            $out = CustomerAccount::where('branch_id',Auth::user()->branch_id)->where('customer_id',$customer->customer_id)->sum("amount_out");
            if(($in-$out) !=0){

                $bal =[
                    'id'=>$customer->customer_id,
                    'name'=>$customer->customer->name,
                    'contact'=>$customer->customer->contact,
                    'balance'=>round($in - $out, 2)
                ];
                array_push($balances,$bal);
            }
        }
        return $balances;
        // return customerResource::collection($customer);


    }

    public function balancedetails($id)
    {

        if(Auth::user()->branch_id == 0){
            $details = CustomerAccount::with('customer')->where('business_id',Auth::user()->business_id)->where('customer_id',$id)->orderBy("created_at", "desc")->get();
        }else{
            $details = CustomerAccount::with('customer')->where('branch_id',Auth::user()->branch_id)->where('customer_id',$id)->orderBy("created_at", "desc")->get();
        }
        return CustomerAccountResource::collection($details);

    }

    public function customersales(Request $request)
    {
        if($request->product == ''){
            if(Auth::user()->branch_id==0){
                $sales = Sale::where('business_id', Auth::user()->business_id)->where('customer_id', $request->customer)->with('items')->orderBy("created_at", "desc")->get();
            }
            else{
                $sales = Sale::where('branch_id', Auth::user()->branch_id)->where('customer_id', $request->customer)->with('items')->orderBy("created_at", "desc")->get();
            }
        }else{
            if(Auth::user()->branch_id==0){
                $sales = Sale::where('business_id', Auth::user()->business_id)->where('customer_id', $request->customer)->with('items')->whereHas('items', function ($query) use($request) {
                    return $query ->where('product_id', $request->product);
                })->orderBy("created_at", "desc")->get();
            }
            else{
                $sales = Sale::where('branch_id', Auth::user()->branch_id)->where('customer_id', $request->customer)->with('items')->whereHas('items', function ($query) use($request) {
                    return $query ->where('product_id', $request->product);
                })->orderBy("created_at", "desc")->get();
            }
        }
        return SaleResource::collection($sales);

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
            'name' => 'required',
        ]);

        $custcheck = Customer::where('name', $request->name)->where('contact', $request->contact)
        ->where('branch_id', Auth::user()->branch_id)->get();
        if ($custcheck->count()>0) {
            return response(['message'=>"This Customer has already been registered"],202);
        }
        else {
            Customer::create([
                'name' => $request->name,
                'contact' => $request->contact?$request->contact:'',
                'user_id' => Auth::user()->id,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
            ]);

            $action = " added a new Customer ".$request->name." with phone number ".$request->contact;
            app('App\Http\Controllers\FunctionController')->log($action,Log::Management);
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
        $customer = Customer::with('orders')->where('id',$id)->get();
        return new CustomerResource($customer);
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
        $customer = Customer::find($id);
        $customer->name = $request->name;
        $customer->contact = $request->contact;
        $customer->save();

        $action = " updated customer ".$request->name." details";
        app('App\Http\Controllers\FunctionController')->log($action,Log::Management);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::find($id);
        $customer->status = 0;
        $customer->save();
        $action = "deleted customer ".$customer->name;
        app('App\Http\Controllers\FunctionController')->log($action,Log::Management);
    }



}
