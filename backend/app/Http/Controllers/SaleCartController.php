<?php

namespace App\Http\Controllers;

use App\Http\Resources\SaleCartResource;
use App\Models\SaleCart;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Dompdf\Dompdf;


class SaleCartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cart = SaleCart::where('branch_id',Auth::user()->branch_id)->where('user_id',Auth::user()->id)->where('hold',0)->orderBy("created_at", "asc")->get();
        return SaleCartResource::collection($cart);
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

     public function generatePdf()
     {
         $pdts = Product::whereColumn('quantity','<=','minimum_quantity')->where('status', 1)->get(); // Get all users from the database
         $html = view('notifications', compact('pdts'))->render(); // Render the view as HTML
         $dompdf = new Dompdf(); // Create a new instance of Dompdf
         $dompdf->loadHtml($html); // Load the HTML into Dompdf
         $dompdf->setPaper('A4', 'portrait'); // Set the paper size and orientation
         $dompdf->render(); // Render the PDF

        // $dompdf->save('/pdf/file.pdf');
        // return response()->json(['url' => '/pdf/file.pdf']);

         return $dompdf->stream('notifications.pdf'); // Output the generated PDF to the browser
     }

    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'product' => 'required|integer',
            'quantity' => 'required',
        ]);

        $check = SaleCart::where('product_id', $request->product)->where('unit', $request->unit)->where('batch_id', $request->batch)->where('user_id',Auth::user()->id)->where('hold',0)
        ->orWhere('code', $request->product)->where('unit', $request->unit)->where('batch_id', $request->batch)->where('user_id',Auth::user()->id)->where('hold',0)->get();
        if(count($check)>0){
            return response(['message'=>"This product already exists in the cart"],202);
        }
        else{
            $product_type = Product::where('id',$request->product)->with('businessproduct')
            ->Orwhere('product_code',$request->product)->with('businessproduct')->where('branch_id', Auth::user()->branch_id)->first();
            if($product_type->is_product){
                if($request->saletype == 1){
                    $selling = ProductUnit::find($request->unit)->selling_price;
                }else{
                    $selling = ProductUnit::find($request->unit)->wholesale_unitprice;
                }
            }
            else{
                if($request->saletype == 1){
                    $selling = $product_type->selling_price;
                }else{
                    $selling = $product_type->wholesale_unitprice;
                }
            }
            $categoryid = ProductCategory::where('name', '=', 'Kitchen-Pdts')->where('business_id', Auth::user()->business_id)->first();
            if($categoryid && $product_type->businessproduct->category_id == $categoryid->id){
                    SaleCart::create([
                        'product_id' => $product_type->id,
                        'quantity' => $request->quantity,
                        'code' => $product_type->product_code ?? '',
                        'quantity_taken' => $request->quantity_taken ?? $request->quantity,
                        'unit' => $request->unit ?? 0,
                        'type' => $request->type,
                        'selling_price' => $selling,
                        'batch_id' => $request->batch,
                        'description' => $request->description??'',
                        'user_id' => Auth::user()->id,
                        'cart_status' => 1,
                        'branch_id' => Auth::user()->branch_id,
                        'business_id' => Auth::user()->business_id,
                    ]);
            }
            else{
                SaleCart::create([
                    'product_id' => $product_type->id,
                    'quantity' => $request->quantity,
                    'code' => $product_type->product_code ?? '',
                    'quantity_taken' => $request->quantity_taken ?? $request->quantity,
                    'unit' => $request->unit ?? 0,
                    'type' => $request->type,
                    'selling_price' => $selling,
                    'batch_id' => $request->batch,
                    'description' => $request->description??'',
                    'user_id' => Auth::user()->id,
                    'branch_id' => Auth::user()->branch_id,
                    'business_id' => Auth::user()->business_id,
                ]);
            }
        }
    }

    public function store1(Request $request)
    {
        // return $request;
        $request->validate([
            'id' => 'required|integer',
            'product' => 'required|integer',
            'quantity' => 'required',
        ]);

        $check = SaleCart::where('product_id', $request->product)->where('user_id',Auth::user()->id)->where('holdId',$request->id)->where('unit',$request->unit)->first();
        if($check){
            $check->quantity = $check->quantity+$request->quantity;
            $check->quantity_taken = $check->quantity_taken+$request->quantity;
            $check->save();
        }
        else{
            $product_type = Product::find($request->product);
            if($product_type->is_product){
                if($request->saletype == 2){
                    $selling = ProductUnit::find($request->unit)->wholesale_unitprice;
                }else{
                    $selling = ProductUnit::find($request->unit)->selling_price;
                }
            }
            else{
                if($request->saletype == 2){
                    $selling = $product_type->wholesale_unitprice;
                }else{
                    $selling = $product_type->selling_price;
                }
            }
            SaleCart::create([
                'product_id' => $request->product,
                'quantity' => $request->quantity,
                'quantity_taken' => $request->quantity,
                'unit' => $request->unit ?? 0,
                'type' => $request->type,
                'selling_price' => $selling,
                'hold' => 1,
                'holdId' => $request->id,
                'description' => $request->description??'',
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
        ]);
        $item = SaleCart::find($id);
        $item->quantity = $request->quantity;
        $item->quantity_taken = $request->quantity_taken ?? $request->quantity;
        $item->selling_price = str_replace(",","",$request->unit_price);
        $item->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = SaleCart::find($id);
        $item->delete();
    }
    public function totalCart(){
        $sums = SaleCart::where('user_id',Auth::user()->id)->where('branch_id', Auth::user()->branch_id)
        ->where('hold',0)->get();
        $tot = 0;
        foreach ($sums as $sum){
            $tot = $tot + ($sum->selling_price * $sum->quantity);
        }
        return $tot;
    }
}
