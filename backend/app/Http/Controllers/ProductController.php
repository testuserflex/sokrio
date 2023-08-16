<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Http\Resources\NewProductResource;
use App\Http\Resources\SellingpriceChangeResource;
use App\Models\BusinessProduct;
use App\Models\Log;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductUnit;
use App\Models\SellingpriceChange;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\Unit;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->branch_id==0){
            $products= Product::with(['stock','businessproduct','productunits'])->where('business_id',Auth::user()->business_id)->where('status',1)->where('is_product',1)->orderBy("created_at", "desc")->get();

        }else{
            $products= Product::with(['stock','businessproduct','productunits'])->where('branch_id',Auth::user()->branch_id)->where('status',1)->where('is_product',1)->orderBy("created_at", "desc")->get();
        }
        return ProductResource::collection($products);
    }

    public function products_data(Request $request)
    {
        if (Auth::user()->branch_id==0){
            if($request->branch_id == 0){
                $products= Product::with(['stock','businessproduct','productunits','branch'])->where('business_id',Auth::user()->business_id)->where('branch_id',$request->branch_id)->where('status',1)->where('is_product',1)->orderBy("created_at", "desc")->get();
            }else{
                $products= Product::with(['stock','businessproduct','productunits','branch'])->where('business_id',Auth::user()->business_id)->where('status',1)->where('is_product',1)->orderBy("created_at", "desc")->get();
            }
        }else{
            $products= Product::with(['stock','businessproduct','productunits'])->where('branch_id',Auth::user()->branch_id)->where('status',1)->where('is_product',1)->orderBy("created_at", "desc")->get();
        }
        return ProductResource::collection($products);
    }

    public function viewpdts(Request $request)
    {
        if(Auth::user()->branch_id==0){
            if($request->branch_id == 0){
                if($request->product != '' && $request->category == ''){
                    $fetchedproducts= Product::with(['stock','businessproduct','productunits','branch'])->where('business_id',Auth::user()->business_id)->where('status',1)->where('is_product',1)->where('product_name', 'LIKE', '%'.$request->product.'%')->orderBy("created_at", "desc")->get();
                }elseif($request->product == '' && $request->category != ''){
                    $fetchedproducts= Product::with(['stock','businessproduct','productunits','branch'])->where('business_id',Auth::user()->business_id)->where('status',1)->where('is_product',1)->whereRelation('businessproduct','category_id',$request->category)->orderBy("created_at", "desc")->get();
                }elseif($request->product != '' && $request->category != ''){
                    $fetchedproducts= Product::with(['stock','businessproduct','productunits'])->where('business_id',Auth::user()->business_id)->where('status',1)->where('is_product',1)->where('product_name', 'LIKE', '%'.$request->product.'%')->whereRelation('businessproduct','category_id',$request->category)
                    ->orWhere('product_code', $request->product)->where('business_id',Auth::user()->business_id)->whereRelation('businessproduct','category_id',$request->category)->where('status',1)->where('is_product',1)->orderBy("created_at", "desc")->get();
                }else{
                    $fetchedproducts= Product::with(['stock','businessproduct','productunits','branch'])->where('business_id',Auth::user()->business_id)->where('status',1)->where('is_product',1)->orderBy("created_at", "desc")->limit('50')->get();
                }
            }else{
                if($request->product != '' && $request->category == ''){
                    $fetchedproducts= Product::with(['stock','businessproduct','productunits','branch'])->where('branch_id',$request->branch_id)->where('status',1)->where('is_product',1)->where('product_name', 'LIKE', '%'.$request->product.'%')->orderBy("created_at", "desc")->get();
                }elseif($request->product == '' && $request->category != ''){
                    $fetchedproducts= Product::with(['stock','businessproduct','productunits','branch'])->where('branch_id',$request->branch_id)->where('status',1)->where('is_product',1)->whereRelation('businessproduct','category_id',$request->category)->orderBy("created_at", "desc")->get();
                }elseif($request->product != '' && $request->category != ''){
                    $fetchedproducts= Product::with(['stock','businessproduct','productunits'])->where('branch_id',$request->branch_id)->where('status',1)->where('is_product',1)->where('product_name', 'LIKE', '%'.$request->product.'%')->whereRelation('businessproduct','category_id',$request->category)
                    ->orWhere('product_code', $request->product)->where('branch_id',$request->branch_id)->whereRelation('businessproduct','category_id',$request->category)->where('status',1)->where('is_product',1)->orderBy("created_at", "desc")->get();
                }else{
                    $fetchedproducts= Product::with(['stock','businessproduct','productunits','branch'])->where('branch_id',$request->branch_id)->where('status',1)->where('is_product',1)->orderBy("created_at", "desc")->limit('50')->get();
                }
            }
        }else{
            if($request->product != '' && $request->category == ''){
                $fetchedproducts= Product::with(['stock','businessproduct','productunits','branch'])->where('branch_id',Auth::user()->branch_id)->where('status',1)->where('is_product',1)->where('product_name', 'LIKE', '%'.$request->product.'%')->orWhere('product_code', $request->product)->where('branch_id',Auth::user()->branch_id)->where('status',1)->where('is_product',1)->orderBy("created_at", "desc")->get();
            }elseif($request->product == '' && $request->category != ''){
                $fetchedproducts= Product::with(['stock','businessproduct','productunits','branch'])->where('branch_id',Auth::user()->branch_id)->where('status',1)->where('is_product',1)->whereRelation('businessproduct','category_id',$request->category)->orderBy("created_at", "desc")->get();
            }elseif($request->product != '' && $request->category != ''){
                $fetchedproducts= Product::with(['stock','businessproduct','productunits'])->where('branch_id',Auth::user()->branch_id)->where('status',1)->where('is_product',1)->where('product_name', 'LIKE', '%'.$request->product.'%')->whereRelation('businessproduct','category_id',$request->category)
                ->orWhere('product_code', $request->product)->where('branch_id',Auth::user()->branch_id)->whereRelation('businessproduct','category_id',$request->category)->where('status',1)->where('is_product',1)->orderBy("created_at", "desc")->get();
            }else{
                $fetchedproducts= Product::with(['stock','businessproduct','productunits','branch'])->where('branch_id',Auth::user()->branch_id)->where('status',1)->where('is_product',1)->orWhere('product_code', $request->product)->where('branch_id',Auth::user()->branch_id)->where('status',1)->where('is_product',1)->orderBy("created_at", "desc")->limit('50')->get();
            }
        }
        return ProductResource::collection($fetchedproducts);
    }


    public function all()
    {
        if (Auth::user()->branch_id==0){
            $products= Product::with(['stock','businessproduct','productunits'])->where('business_id',Auth::user()->business_id)->where('status',1)->get();

        }else{
            $products= Product::with(['stock','businessproduct','productunits'])->where('branch_id',Auth::user()->branch_id)->where('status',1)->get();
        }
        return ProductResource::collection($products);
    }

    public function fetchpdts()
    {
        if (Auth::user()->branch_id==0){
            $products= Product::with(['stock','businessproduct','productunits'])->where('business_id',Auth::user()->business_id)->where('status',1)->where('is_product',1)->orderBy("created_at", "desc")->get();

        }else{
            $products= Product::with(['stock','businessproduct','productunits'])->where('branch_id',Auth::user()->branch_id)->where('status',1)->where('is_product',1)->orderBy("created_at", "desc")->get();
        }
        return NewProductResource::collection($products);
    }

    public function outofstock()
    {
        if (Auth::user()->branch_id==0){
            $product= Product::with(['stock','businessproduct','productunits'])->where('business_id',Auth::user()->business_id)->where('quantity','<=',0)->where('status',1)->where('is_product',1)->orderBy("created_at", "desc")->get();

        }else{
            $products= Product::with(['stock','businessproduct','productunits'])->where('branch_id',Auth::user()->branch_id)->where('quantity','<=',0)->where('status',1)->where('is_product',1)->orderBy("created_at", "desc")->get();
        }
        return ProductResource::collection($products);
    }


    public function instock()
    {
        if (Auth::user()->branch_id==0){
            $products= Product::with(['stock','businessproduct','productunits'])->where('business_id',Auth::user()->business_id)->where('quantity','>',0)->where('status',1)->where('is_product',1)->orderBy("quantity", "desc")->get();

        }else{
            $products= Product::with(['stock','businessproduct','productunits'])->where('branch_id',Auth::user()->branch_id)->where('quantity','>',0)->where('status',1)->where('is_product',1)->orderBy("quantity", "desc")->get();
        }
        return ProductResource::collection($products);
    }

    public function branch(Request $request)
    {
        $request->validate([
            'source_branch' => 'required',
        ]);

            $products= Product::with(['stock','businessproduct','productunits'])->where('branch_id',$request->source_branch)->where('status',1)->where('is_product',1)->orderBy("created_at", "desc")->get();
            return ProductResource::collection($products);
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
            'category' => 'required',
            'product' => 'required',
            'minimum' => 'required',
            'selling_price' => 'required',
            'unit' => 'required',
        ]);
        if($request->buying_price){
            $buying = str_replace(",","",$request->buying_price);
        }
        else{
            $buying = 0;
        }
        $selling = str_replace(",","",$request->selling_price);
        $reserve = $request->reserve_price ?? $selling;
        $wholesaleprice = $request->wholesale_unitprice?str_replace(",","",$request->wholesale_unitprice):0;
        $wholesale_reserve = $request->wholesale_reserveprice?str_replace(",","",$request->wholesale_reserveprice): $wholesaleprice;
        // check if unit exist
        $unitcheck = Unit::where('name', $request->unit)
        ->where('branch_id', Auth::user()->branch_id)->first();
        if ($unitcheck) {
            $uid = $unitcheck->id;
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

        $catcheck = ProductCategory::where('name', $request->category)
        ->where('business_id', Auth::user()->business_id)->first();
        if ($catcheck) {
            $pdtcat = $catcheck->id;
        }
        else {
            $category = ProductCategory::create([
                'name' => $request->category,
                'user_id' => Auth::user()->id,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
            ]);
            $pdtcat = $category->id;
        }

        // Check if it is a new business product
        $bproduct = BusinessProduct::where('name', $request->product)->where('business_id', Auth::user()->business_id)->where('status', 1)->first();
        if($bproduct){
            $item = $bproduct->id;
        }
        else{
            // create business product
            $bpdt = BusinessProduct::create([
                'name' => $request->product,
                'category_id' => $pdtcat,
                'user_id' => Auth::user()->id,
                'business_id' => Auth::user()->business_id,
            ]);
            $item = $bpdt->id;

        }

        if($request->code != ''){
            $products = Product::where('item_id', $item)->where('branch_id', Auth::user()->branch_id)->orWhere('product_code', $request->code)->where('branch_id', Auth::user()->branch_id)->where('item_id', $item)->first();
        }else{
            $products = Product::where('item_id', $item)->where('branch_id', Auth::user()->branch_id)->where('branch_id', Auth::user()->branch_id)->first();
        }

        if ($products && $products->status == 0) {
            $products->product_name = $request->name ?? BusinessProduct::find($item)->name;
            $products->product_code = $request->code;
            $products->minimum_quantity = $request->minimum ?? 1;
            $products->quantity = str_replace(",","",$request->quantity) ?? 0;
            $products->selling_price = str_replace(",","",$request->selling_price);
            $products->reserve_price = str_replace(",","",$reserve);
            $products->wholesale_unitprice = $wholesaleprice;
            $products->wholesale_reserveprice = $wholesale_reserve;
            $products->user_id = Auth::user()->id;
            $products->status = 1;
            $products->save();
        }
        
        // else if ($products && $products->status == 1) {
        //     return response(['message'=>"The product with the same code or global product already exists"], 202);
        // }
        else {

            $product= Product::create([
                'product_name' => $request->name ?? BusinessProduct::find($item)->name,
                'item_id' => $item,
                'product_code' => $request->code,
                'minimum_quantity' => $request->minimum ?? 1,
                'quantity' => str_replace(",","",$request->quantity) ?? 0,
                'is_product' => 1,
                'selling_price' => str_replace(",","",$request->selling_price),
                'reserve_price' => str_replace(",","",$reserve),
                'wholesale_unitprice' => $wholesaleprice,
                'wholesale_reserveprice' => $wholesale_reserve,
                'unit_id' => $uid,
                'vat' => $request->vat ?? 0,
                'user_id' => Auth::user()->id,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
            ]);
            if($request->otherunits){
                $pdtunits =  $request->otherunits;
                foreach($pdtunits as $punit){
                    if($punit['unit']){

                        $selling = str_replace(",","",$punit['selling_price']);
                        if($punit['reserve_price']){
                            $reserve = str_replace(",","",$punit['reserve_price']);
                        }
                        else{
                            $reserve = $selling;
                        }

                        $wholesalereserve = $punit['wholesale_reserveprice'] ?? $punit['wholesale_unitprice'];
                        ProductUnit::create([
                            'product_id' =>$product->id,
                            'product_code' =>$punit['code'],
                            'unit_id' => $punit['unit'],
                            'base_quantity' => str_replace(",","",$punit['base_quantity']),
                            'selling_price' => str_replace(",","",$punit['selling_price']),
                            'wholesale_unitprice' => $punit['wholesale_unitprice']?str_replace(",","",$punit['wholesale_unitprice']):0,
                            'reserve_price' => $reserve,
                            'wholesale_reserveprice' => $wholesalereserve?str_replace(",","",$wholesalereserve):0,
                            'branch_id' => Auth::user()->branch_id,
                            'business_id' => Auth::user()->business_id,
                            'user_id' => Auth::user()->id,
                        ]);
                    }
                }
            }
            ProductUnit::create([
                'product_id' =>$product->id,
                'product_code' =>$request->code,
                'unit_id' => $uid,
                'base_quantity' => 1,
                'selling_price' => $product->selling_price,
                'reserve_price' => $product->reserve_price,
                'wholesale_unitprice' => $wholesaleprice,
                'wholesale_reserveprice' => $wholesale_reserve,
                'is_base' => 1,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
                'user_id' => Auth::user()->id,

            ]);
            Stock::create([
                'product_id' =>$product->id,
                'quantity' => str_replace(",","",$request->quantity) ?? 0,
                'buying_price' => $buying,
                'average_buyingprice' => $buying,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
                'user_id' => Auth::user()->id,
            ]);
            if ($product->quantity > 0){
                StockMovement::create([
                    'product_id' =>$product->id,
                    'quantity_in' => str_replace(",","",$request->quantity),
                    'quantity_out' => 0,
                    'date' => date('Y-m-d'),
                    'type' => 0,
                    'transaction_id' => 0,
                    'branch_id' => Auth::user()->branch_id,
                    'business_id' => Auth::user()->business_id,
                    'user_id' => Auth::user()->id,
                ]);

            }

            $action = " added a new product ".$product->name;
            app('App\Http\Controllers\FunctionController')->log($action,Log::Stock);

            return response(['message'=>"This product has been added successfully"], 200);
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
            'name' => 'required',
            'minimum' => 'required',
            'selling_price' => 'required',
            'reserve_price' => 'required',
        ]);

        $wholesale_price = $request->wholesale_unitprice?str_replace(",","",$request->wholesale_unitprice):0;

        if($request->code != ''){
            $products = Product::where('product_name', $request->name)->where('branch_id', Auth::user()->branch_id)->where('id', '!=', $id)->orWhere('product_code', $request->code)->where('id', '!=', $id)->where('branch_id', Auth::user()->branch_id)->first();
        }else{
            $products = Product::where('product_name', $request->name)->where('branch_id', Auth::user()->branch_id)->where('id', '!=', $id)->where('branch_id', Auth::user()->branch_id)->first();
        }

        if ($products) {
            return response(['message'=>"The product with the same name or code already exists"], 202);
        }
        $product=Product::find($id)->update([
            'product_name' => $request->name,
            'product_code' => $request->code??'',
            'minimum_quantity' => str_replace(",","",$request->minimum),
            'selling_price' => str_replace(",","",$request->selling_price),
            'wholesale_unitprice' => $wholesale_price,
            'reserve_price' => str_replace(",","",$request->reserve_price) ?? str_replace(",","",$request->selling_price),
            'wholesale_reserveprice' => $request->wholesale_reserveprice?str_replace(",","",$request->wholesale_reserveprice) : $wholesale_price,
            'vat' => $request->vat ?? 0

        ]);

        $productprice = ProductUnit::where('product_id',$id)->where('branch_id',Auth::user()->branch_id)->where('is_base',1)->first('selling_price');
        if($productprice->selling_price != $request->selling_price){
            $pricechange = SellingpriceChange::create([
                'product_id' => $id,
                'old_price' => $productprice->selling_price,
                'new_price' => $request->selling_price,
                'user_id' => Auth::user()->id,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
            ]);
        }
        ProductUnit::where('product_id',$id)->where('branch_id',Auth::user()->branch_id)->where('is_base',1)
        ->update(['product_code'=>$request->code,
        'selling_price'=>$request->selling_price,
            'reserve_price'=>$request->reserve_price ?? $request->selling_price,
            'wholesale_unitprice'=>$wholesale_price,
            'wholesale_reserveprice' => $request->wholesale_reserveprice?str_replace(",","",$request->wholesale_reserveprice) : $wholesale_price]);
        $action = "Update details of product ".$request->name;
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
        $prodcudt= Product::find($id);
        $prodcudt->status=0;
        $prodcudt->save();
        $stock= Stock::where('product_id', $id)->first();
        $stock->status = 0;
        $stock->save();
        $action = "Deleted  product ".$prodcudt->product_name;
        app('App\Http\Controllers\FunctionController')->log($action,Log::Stock);
    }


    // Selling Price Changes
    public function price_changes()
    {
        if(Auth::user()->branch_id==0){
            $pricechange = SellingpriceChange::where('business_id', Auth::user()->business_id)->orderBy("created_at", "desc")->get();
        }
        else{
            $pricechange = SellingpriceChange::where('branch_id', Auth::user()->branch_id)->orderBy("created_at", "desc")->get();
        }
        return SellingpriceChangeResource::collection($pricechange);
    }

    // Transfer products
    public function transfer_products(Request $request)
    {
        $products= Product::with(['stock','unit','businessproduct','productunits'])->where('branch_id',Auth::user()->branch_id)->where('status',1)->get();
        foreach($products as $product){
            $checkpdt = Product::where('product_name', $product->product_name)->where('branch_id', $request->branch)->first();
            if(!$checkpdt){
                    // check if unit exist
                if($product->unit_id != 0){
                    $unitcheck = Unit::where('name', $product->unit->name)
                    ->where('branch_id', Auth::user()->branch_id)->first();
                    $unit = Unit::create([
                        'name' => $product->unit->name,
                        'symbol' => $product->unit->symbol,
                        'user_id' => Auth::user()->id,
                        'branch_id' => $request->branch,
                        'business_id' => Auth::user()->business_id,
                    ]);
                    $uid = $unit->id;
                }
                $newproducts = Product::where('item_id', $product->businessproduct->id)->where('branch_id', $request->branch)->get();
                if ($newproducts->count() == 0) {
                    $newproduct= Product::create([
                        'product_name' => $product->product_name ?? $product->businessproduct->name,
                        'item_id' => $product->businessproduct->id,
                        'product_code' => $product->product_code??'',
                        'minimum_quantity' => $product->minimum_quantity ?? 1,
                        'quantity' => 0,
                        'is_product' => 1,
                        'selling_price' => $product->selling_price,
                        'reserve_price' => $product->reserve_price,
                        'unit_id' => $uid ?? 0,
                        'vat' => 0,
                        'user_id' => Auth::user()->id,
                        'branch_id' => $request->branch,
                        'business_id' => Auth::user()->business_id,
                    ]);

                    ProductUnit::create([
                        'product_id' =>$newproduct->id,
                        'unit_id' => $uid,
                        'base_quantity' => 1,
                        'selling_price' => $newproduct->selling_price,
                        'reserve_price' => $newproduct->reserve_price,
                        'is_base' => 1,
                        'branch_id' => $request->branch,
                        'business_id' => Auth::user()->business_id,
                        'user_id' => Auth::user()->id,
                    ]);

                    if($product->unit_id != 0){
                        $pdtunits = ProductUnit::where('product_id', $product->id)->with(['unit'])->get();
                        foreach($pdtunits as $pdtunit){
                            $branchunit = Unit::where('name', $pdtunit->unit->name)
                            ->where('branch_id', $request->branch)->first();
                            if(!$branchunit){
                                $newunit = Unit::create([
                                    'name' => $pdtunit->unit->name,
                                    'symbol' => $pdtunit->unit->symbol,
                                    'user_id' => Auth::user()->id,
                                    'branch_id' => $request->branch,
                                    'business_id' => Auth::user()->business_id,
                                ]);
                                $newid = $newunit->id;
                                ProductUnit::create([
                                    'product_id' =>$newproduct->id,
                                    'unit_id' => $newid,
                                    'base_quantity' => $pdtunit->base_quantity,
                                    'selling_price' => $pdtunit->selling_price,
                                    'reserve_price' => $pdtunit->reserve_price,
                                    'is_base' => $pdtunit->is_base,
                                    'branch_id' => $request->branch,
                                    'business_id' => Auth::user()->business_id,
                                    'user_id' => Auth::user()->id,
                                ]);
                            }
                        }
                    }
                    Stock::create([
                        'product_id' =>$newproduct->id,
                        'quantity' => 0,
                        'buying_price' => $product->stock ? $product->stock->buying_price:0,
                        'average_buyingprice' => $product->stock ? $product->stock->buying_price:0,
                        'branch_id' => $request->branch,
                        'business_id' => Auth::user()->business_id,
                        'user_id' => Auth::user()->id,
                    ]);
                }
            }

        }
        $branch = Branch::where('id',$request->branch)->first();
        $action = " transfered products from ".Auth::user()->branch->name.'to '.$branch->name;
        app('App\Http\Controllers\FunctionController')->log($action,Log::Stock);
    }



}
