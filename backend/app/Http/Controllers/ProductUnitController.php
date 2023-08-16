<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Log;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->branch_id==0){
            $product = Product::where('business_id', Auth::user()->business_id)->where('status', 1)->orderBy("created_at", "desc")->get();
        }
        else{
            $product = Product::where('branch_id', Auth::user()->branch_id)->where('status', 1)->orderBy("created_at", "desc")->get();
        }
        return ProductResource::collection($product);
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
            'product' => 'required|integer',
            'unit' => 'required',
            'base_quantity' => 'required',
            'selling_price' => 'required',
        ]);
        if($request->base_quantity<1){
            return response(['message'=>"Unit Quantity should be greater than or equal to 1"],201);
        }
        // check if unit exist
        $unitCheck = Unit::where('name', $request->unit)
        ->where('branch_id', Auth::user()->branch_id)->first();
        if ($unitCheck) {
            $uid = $unitCheck->id;
        }
        else {
            $unit = Unit::create([
                'name' => $request->unit,
                'symbol' => $request->unit,
                'user_id' => Auth::user()->id,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
            ]);

            $uid = $unit->id;
        }
        $productunitcheck = ProductUnit::where('unit_id', $uid)->where('product_id', $request->product)
        ->where('branch_id', Auth::user()->branch_id)->get();
        if ($productunitcheck->count()>0) {
            return response(['message'=>"This product unit already exists"],201);
        }
        else {
            $selling = str_replace(",","",$request->selling_price);
            if($request->reserve_price){
                $reserve = str_replace(",","",$request->reserve_price);
            }
            else{
                $reserve = $selling;
            }

            $wholesale_price = $request->wholesale_unitprice?str_replace(",","",$request->wholesale_unitprice):0;

            ProductUnit::create([
                'product_id' =>$request->product,
                'product_code' =>$request->code,
                'unit_id' => $uid,
                'base_quantity' => str_replace(",","",$request->base_quantity),
                'selling_price' => str_replace(",","",$request->selling_price),
                'reserve_price' => $reserve,
                'wholesale_unitprice' => $wholesale_price,
                'wholesale_reserveprice' => $request->wholesale_reserveprice?str_replace(",","",$request->wholesale_reserveprice) : $wholesale_price,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
                'user_id' => Auth::user()->id,
            ]);

            $action = " added a new unit ".Unit::find($uid)->name." to a product ".Product::find($request->product)->product_name;
            app('App\Http\Controllers\FunctionController')->log($action,Log::Stock);
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
        // check if unit exist
        $unitCheck = Unit::where('name', $request->unit)->where('branch_id', Auth::user()->branch_id)->first();
        if ($unitCheck) {
            $uid = $unitCheck->id;
        }
        else {
            $unit = Unit::create([
                'name' => $request->unit,
                'symbol' => $request->unit,
                'user_id' => Auth::user()->id,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
            ]);

            $uid = $unit->id;
        }
        $product = Product::where("product_name", $request->product)->where('branch_id', Auth::user()->branch_id)->first();
        $productunit = ProductUnit::find($id);
        $productunit->product_id = $product->id;
        $productunit->product_code = $request->code;
        $productunit->unit_id = $uid;
        $productunit->base_quantity = str_replace(",","",$request->base_quantity);
        $productunit->selling_price = str_replace(",","",$request->selling_price);
        $productunit->reserve_price = str_replace(",","", $request->reserve_price);
        $productunit->wholesale_reserveprice = str_replace(",","", $request->wholesalereserve_price??0);
        $productunit->wholesale_unitprice = str_replace(",","", $request->wholesale_price??0);
        $productunit->save();
        if($productunit->is_base == 1){
            $product->unit_id = $uid;
            $product->save();
        }

        $action = " updated product unit ".Unit::find($uid)->name." for a product ".Product::find($product->id)->product_name;
        app('App\Http\Controllers\FunctionController')->log($action,Log::Stock);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $productunit = ProductUnit::find($id);
        if($productunit->is_base=="1"){
            return response(['message'=>"This is a base product unit and cannot be removed"],201);
        }
        else{
            $productunit->delete();
            return response(['message'=>"Product unit deleted successfully"],200);
        }
    }

    public function changeBase(Request $request){
        $id = $request->id;
        $productunit = ProductUnit::find($id);
        $unitId = $productunit->unit_id;
        $product = $productunit->product_id;
        $selling = $productunit->selling_price;
        $reserve = $productunit->reserve_price;
        ProductUnit::where('product_id',$product)->where('branch_id',Auth::user()->branch_id)
        ->update(['is_base'=>0]);
        ProductUnit::where('product_id',$product)->where('branch_id',Auth::user()->branch_id)->where('id',$id)
        ->update(['is_base'=>1,'base_quantity'=>1]);
        Product::where('id',$product)->where('branch_id',Auth::user()->branch_id)
        ->update(['unit_id'=>$unitId, 'selling_price'=>$selling, 'reserve_price'=>$reserve]);
    }
}
