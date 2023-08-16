<?php

namespace App\Http\Controllers;

use App\Http\Resources\BusinessResource;
use App\Models\Business;
use App\Models\BusinessSetting;
use App\Models\Country;
use App\Models\Log;
use App\Models\SmsBalance;
use App\Models\Permission;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Purchase;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $business = Business::all();
        $countries = Country::all();
        return view('businesses.businesses')->with(['businesses'=>BusinessResource::collection($business), 'countries'=>$countries]);
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
        $file = $request->file('logo');
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'country' => 'required',
            'currency' => 'required',
        ]);

        $business = Business::create([
            'name' => $request->name,
            'address' => $request->address,
            'website' => $request->website,
            'email' => $request->email,
            'phone1' => $request->phone,
            'phone2' => $request->phone2,
            'tin' => $request->tin,
            'country_id' => $request->country,
            'currency_code' => $request->currency,
            'user_id' => Auth::user()->id,
        ]);
        if (!empty($request->logo)) {
            $file =$request->file('logo');
            $extension = $file->getClientOriginalExtension();
            $filename = time().'.' . $extension;
            $file->move(public_path('assets/logos/'), $filename);
            $business->logo = 'public/assets/logos/'.$filename;
            $business->save();
        }

         // insert into business setting
         BusinessSetting::create([
            'business_id' => $business->id
         ]);

        Log::create([
            'activity' => "Added a new business ".$business->name,
            'category' => 0,
            'user_id' => Auth::user()->id,
            'branch_id' => 0,
            'business_id' =>0,
        ]);

        $business = Business::all();
        $countries = Country::all();
        return view('businesses.businesses')->with(['businesses'=>BusinessResource::collection($business), 'countries'=>$countries]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $business = Business::find(base64_decode($id));
        $countries = Country::all();
        return view('businesses.edit')->with(['business'=>new BusinessResource($business),'countries'=>$countries]);
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
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'country' => 'required',
            'currency' => 'required',
        ]);

        $business = Business::find($id);
        $business->name = $request->name;
        $business->email = $request->email;
        $business->website = $request->website;
        $business->phone1 = $request->phone;
        $business->phone2 = $request->phone2;
        $business->tin = $request->tin;
        $business->address = $request->address;
        $business->country_id = $request->country;
        $business->currency_code = $request->currency;
        $business->save();
        if (!empty($request->logo)) {
            $file =$request->file('logo');
            $extension = $file->getClientOriginalExtension();
            $filename = time().'.' . $extension;
            $file->move(public_path('assets/logos/'), $filename);
            $business->logo = 'public/assets/logos/'.$filename;
            $business->save();
        }

        Log::create([
            'activity' => "Updated details for a business ".$business->name,
            'category' => 0,
            'user_id' => Auth::user()->id,
            'branch_id' => 0,
            'business_id' =>0,
        ]);

        $businessx = Business::all();
        $countries = Country::all();
        return redirect('/businesses')->with(['businesses'=>BusinessResource::collection($businessx), 'countries'=>$countries]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $business = Business::find($id);
        if ($business->status==0){
            $action = "Activated business ".$business->name;
            $business->status = 1;
        }
        else {
            $action = "Deactivated business ".$business->name;
            $business->status = 0;
        }
        $business->save();
        Log::create([
            'activity' => $action,
            'category' => 0,
            'user_id' => Auth::user()->id,
            'branch_id' => 0,
            'business_id' =>0,
        ]);

        $businessx = Business::all();
        $countries = Country::all();
        return redirect('/businesses')->with(['businesses'=>BusinessResource::collection($businessx), 'countries'=>$countries]);
    }

    public function stores($id)
    {
        $business = Business::find($id);
        if ($business->store==0){
            $action = "Activated store for business ".$business->name;
            $business->store = 1;
        }
        else {
            $action = "Deactivated store for business ".$business->name;
            $business->store = 0;
        }
        $business->save();
        $permissions = Permission::where('business_id', $id)->get()->min('id');
        $Adminpermission = Permission::where('id', $permissions)->update([
            'add_store_stock' => 1,
            'store_tab' => 1,
            'view_store_stock' => 1,
            'edit_store_stock' => 1,
            'reconcile_store_stock' => 1,
            'savereconcile_store_stock' => 1,
            'view_storeprice_changes' => 1,
        ]);
        Log::create([
            'activity' => $action,
            'category' => 0,
            'user_id' => Auth::user()->id,
            'branch_id' => 0,
            'business_id' =>0,
        ]);

        $businessx = Business::all();
        $countries = Country::all();
        return redirect('/businesses')->with(['businesses'=>BusinessResource::collection($businessx), 'countries'=>$countries]);
    }

    public function settings(){
        $business = Business::where('id', Auth::user()->business_id)->first();
        return ["status"=>$business->status,"store"=>$business->store];
    }

    public function income_statement(Request $request){
        if(Auth::user()->branch_id==0){
            $total_sales = Sale::where('business_id', Auth::user()->business_id)->whereBetween('sale_date', [$request->datefrom, $request->dateto])->sum('total');
            $costof_sales = SaleDetail::where('business_id', Auth::user()->business_id)->whereHas('sale', function ($query) use($request) {
                return $query ->whereBetween("sale_date", [$request->datefrom, $request->dateto]);
            })->sum('buying_price');
            $total_purchases = Purchase::where('business_id', Auth::user()->business_id)->whereBetween('date', [$request->datefrom, $request->dateto])->sum('total');
            $total_expenses = Expense::where('business_id', Auth::user()->business_id)->whereBetween('date', [$request->datefrom, $request->dateto])->sum('amount');
        }
        else{
            $total_sales = Sale::where('branch_id', Auth::user()->branch_id)->whereBetween('sale_date', [$request->datefrom, $request->dateto])->sum('total');
            $costof_sales = SaleDetail::where('branch_id', Auth::user()->branch_id)->whereHas('sale', function ($query) use($request) {
                return $query ->whereBetween("sale_date", [$request->datefrom, $request->dateto]);
            })->sum('buying_price');
            $total_purchases = Purchase::where('branch_id', Auth::user()->branch_id)->whereBetween('date', [$request->datefrom, $request->dateto])->sum('total');
            $total_expenses = Expense::where('branch_id', Auth::user()->branch_id)->whereBetween('date', [$request->datefrom, $request->dateto])->sum('amount');
        }

        $statement = [
            'total_sales' => number_format($total_sales),
            'costof_sales' => number_format($costof_sales),
            'total_purchases' => number_format($total_purchases),
            'total_expenses' => number_format($total_expenses)
        ];

        return $statement;
    }
}
