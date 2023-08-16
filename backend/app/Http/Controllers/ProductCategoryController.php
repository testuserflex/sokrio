<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCategoryResource;
use App\Models\BusinessProduct;
use App\Models\Log;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product_categories = ProductCategory::where('business_id', Auth::user()
            ->business_id)->where('status', 1)->orderBy("created_at", "desc")->get();

        return ProductCategoryResource::collection($product_categories);
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

        $catcheck = ProductCategory::where('name', '=', $request->name)
            ->where('business_id', Auth::user()->business_id)->where('status',1)->get();
        if ($catcheck->count()>0) {
            return response(['message'=>"This category already exists"],202);
        }
        else {
            ProductCategory::create([
                'name' => $request->name,
                'user_id' => Auth::user()->id,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
            ]);

            $action = " added a new product category ".$request->name;
            app('App\Http\Controllers\FunctionController')->log($action,Log::Stock);
            return response(['message'=>"Product category $request->name has been added successfully"],200);
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
        $category = ProductCategory::find($id);
        $category->name = $request->name;
        $category->save();

        $action = " Updated product Categories ".$request->name." details";
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
        $category = ProductCategory::find($id);
        $check = BusinessProduct::where('category_id',$id)->where('business_id',Auth::user()->business_id)->get();
        if ($check->count()<1){
            $category->delete();
            $action = "deleted Product category ".$category->name;
            app('App\Http\Controllers\FunctionController')->log($action,Log::Stock);
            return response(['message'=>"Product Category $category->name successfully Deleted"],200);
        }else{
            return response(['message'=>"Category $category->name is in Use and cannot be  Deleted"],202);

        }


    }
}
