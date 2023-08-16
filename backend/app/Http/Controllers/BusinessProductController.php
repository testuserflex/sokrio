<?php

namespace App\Http\Controllers;

use App\Http\Resources\BusinessProductResource;
use App\Http\Resources\ProductResource;
use App\Models\BusinessProduct;
use App\Models\Log;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusinessProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = BusinessProduct::with('user')->where('business_id', Auth::user()
            ->business_id)->where('status', 1)->get();

        return BusinessProductResource::collection($product);

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
            'category' => 'required',
        ]);
        $catcheck = ProductCategory::where('name', '=', $request->category)
            ->where('business_id', Auth::user()->business_id)->where('status',1)->first();
        if ($catcheck) {
            $cat = $catcheck->id;
        }
        else {
            $catadd = ProductCategory::create([
                'name' => $request->category,
                'user_id' => Auth::user()->id,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
            ]);

            $cat = $catadd->id;
        }
        $productcheck = BusinessProduct::where('name', $request->name)->where('business_id', Auth::user()->business_id)->where('status',1)->get();
        if ($productcheck->count()>0) {
            return response(['message'=>"This product  already exists"],201);
        }
        else {
            BusinessProduct::create([
                'name' => $request->name,
                'category_id' => $cat,
                'user_id' => Auth::user()->id,
                'business_id' => Auth::user()->business_id,
            ]);

            $action = " added a new product  ".$request->name." in category ".ProductCategory::find($cat)->name;
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
//        return $request;

        $product = BusinessProduct::find($id);
        $product->name = $request->name;
        $catcheck = ProductCategory::where('name', $request->category)
            ->where('business_id', Auth::user()->business_id)->where('status',1)->first();
        if ($catcheck) {
            $cat = $catcheck->id;
        }
        else {
            $catadd = ProductCategory::create([
                'name' => $request->category,
                'user_id' => Auth::user()->id,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
            ]);

            $cat = $catadd->id;
        }
        $product->category_id = $cat;
        $product->save();

        $action = " updated product  $request->name details";
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
        $product = BusinessProduct::find($id);
        $check = Product::where('item_id',$id)->where('business_id',Auth::user()->business_id)->get();
        if ($check->count()<1){
            $product->delete();
            $action = "deleted Product Business ".$product->name;
            app('App\Http\Controllers\FunctionController')->log($action,Log::Stock);
            return response(['message'=>"Product  $product->name successfully Deleted"],200);
        }else{
            return response(['message'=>"Product $product->name is in Use and cannot be  Deleted"],201);

        }
    }
}
