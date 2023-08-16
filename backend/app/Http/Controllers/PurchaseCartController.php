<?php

namespace App\Http\Controllers;

use App\Http\Resources\PurchaseCartResource;
use App\Models\PurchaseCart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseCartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cart = PurchaseCart::where('branch_id',Auth::user()->branch_id)->where('user_id',Auth::user()->id)
        ->where('category',0)->orderBy("created_at", "desc")->get();
        return PurchaseCartResource::collection($cart);
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
            'quantity' => 'required',
            'unit_price' => 'required',
            'unit' => 'required',
            'source' => 'required',
            'category' => 'required',
        ]);

        $check = PurchaseCart::where('product_id', $request->product)->where('category',$request->category)
        ->where('user_id',Auth::user()->id)->where('batch',$request->batch)->where('branch_id',Auth::user()->branch_id)->
        orWhere('code', $request->product)->where('category',$request->category)
        ->where('user_id',Auth::user()->id)->where('batch',$request->batch)->where('branch_id',Auth::user()->branch_id)->get();
        if(count($check)>0){
            return response(['message'=>"This product already exists in the cart"],201);
        }
        else{
            $product = Product::where('id',$request->product)->orwhere('product_code',$request->product)->where('branch_id', Auth::user()->branch_id)->first();
            PurchaseCart::create([
                'product_id' => $product->id,
                'code' => $product->product_code,
                'quantity' => $request->quantity,
                'unit_price' => str_replace(",","",$request->unit_price),
                'unit_sellingprice' => $request->unit_sellingprice?str_replace(",","",$request->unit_sellingprice):$product->selling_price,
                'unit' => $request->unit,
                'expiry' => $request->expiry,
                'batch' => $request->batch,
                'category' => $request->category,
                'source' => $request->source,
                'user_id' => Auth::user()->id,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
            ]);
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
        $request->validate([
            'product' => 'required|integer',
            'quantity' => 'required',
            'unit_price' => 'required',
        ]);
        $purchase = PurchaseCart::find($id);
        $purchase->quantity = $request->quantity;
        $purchase->unit_price = str_replace(",","",$request->unit_price);
        $purchase->unit_sellingprice = str_replace(",","",$request->unit_sellingprice);
        $purchase->expiry = $request->expiry;
        $purchase->batch = $request->batch;
        $purchase->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $purchase = PurchaseCart::find($id);
        $purchase->delete();
    }

    public function totalCart(){
        $sums = PurchaseCart::where('user_id',Auth::user()->id)->where('branch_id', Auth::user()->branch_id)
        ->where('category',0)->get();
        $tot = 0;
        foreach ($sums as $sum){
            $tot = $tot + ($sum->unit_price * $sum->quantity);
        }
        return $tot;
    }
}
