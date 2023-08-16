<?php

namespace App\Http\Controllers;

use App\Http\Resources\ServiceResource;
use App\Models\BusinessProduct;
use App\Models\Log;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Service;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->branch_id==0){
            $products= Product::with(['businessproduct','branch'])->where('business_id',Auth::user()->business_id)->where('status',1)->where('is_product',0)->orderBy("created_at", "desc")->get();

        }else{
            $products= Product::with(['businessproduct','branch'])->where('branch_id',Auth::user()->branch_id)->where('status',1)->where('is_product',0)->orderBy("created_at", "desc")->get();
        }
        return ServiceResource::collection($products);
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
            'product' => 'required',
            'selling_price' => 'required',
        ]);

        if($request->cost_price){
            $cost = str_replace(',', '', $request->cost_price);
        }
        else{
            $cost = 0;
        }
        // Check if it is a new business product
        $bproduct = BusinessProduct::where('name', $request->product)->where('business_id', Auth::user()->business_id)->where('status', 1)->first();
        if($bproduct){
            $item = $bproduct->id;
        }
        else{
            $catcheck = ProductCategory::where('business_id', Auth::user()->business_id)->where('status',1)->first();
            if ($catcheck) {
                $cat = $catcheck->id;
            }
            else {
                $catadd = ProductCategory::create([
                    'name' => "Default",
                    'user_id' => Auth::user()->id,
                    'branch_id' => Auth::user()->branch_id,
                    'business_id' => Auth::user()->business_id,
                ]);

                $cat = $catadd->id;
            }
            // create business product
            $bpdt = BusinessProduct::create([
                'name' => $request->product,
                'category_id' => $cat,
                'user_id' => Auth::user()->id,
                'business_id' => Auth::user()->business_id,
            ]);
            $item = $bpdt->id;

        }

        $products = Product::where('item_id', $item)->where('branch_id', Auth::user()->branch_id)->where('status',1)->get();
        if ($products->count()>0) {
            return response(['message'=>"This product already exists"],202);
        }
        else {

            $product= Product::create([
                'product_name' => $request->name ?? BusinessProduct::find($item)->name,
                'item_id' => $item,
                'is_product' => 0,
                'selling_price' => str_replace(",","",$request->selling_price),
                'reserve_price' => str_replace(",","",$request->selling_price),
                'unit_id' => 0,
                'vat' => 0,
                'user_id' => Auth::user()->id,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
            ]);
            Stock::create([
                'product_id' =>$product->id,
                'quantity' => 0,
                'buying_price' => $cost,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
                'user_id' => Auth::user()->id,
            ]);

            $action = " added a new service ".$product->name;
            app('App\Http\Controllers\FunctionController')->log($action,Log::Stock);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'selling_price' => 'required',
        ]);
        if($request->cost_price){
            $cost = str_replace(',', '', $request->cost_price);
        }
        else{
            $cost = 0;
        }
        $product=Product::find($id);
        $product->product_name = $request->name;
        $product->selling_price = str_replace(",","",$request->selling_price);
        $product->save();
        $stock = Stock::where('product_id',$id)->first();
        $stock->buying_price = $cost;
        $stock->save();
        $action = "Update details of a service ".$request->name;
        app('App\Http\Controllers\FunctionController')->log($action,Log::Stock);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $prodcudt= Product::find($id);
        $prodcudt->status=0;
        $prodcudt->save();
        $action = "Deleted a Service ".$prodcudt->product_name;
        app('App\Http\Controllers\FunctionController')->log($action,Log::Stock);
    }
}
