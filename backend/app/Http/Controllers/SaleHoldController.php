<?php

namespace App\Http\Controllers;

use App\Http\Resources\SaleHoldResource;
use App\Models\SaleCart;
use App\Models\SaleHold;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleHoldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sales = SaleHold::where('user_id', Auth::user()->id)->where('branch_id', Auth::user()->branch_id)->whereDate('created_at',date('Y-m-d'))->limit(15)->orderBy('created_at','desc')->get();
        return SaleHoldResource::collection($sales);
    }

    public function all()
    {
        if(Auth::user()->branch_id == 0){
            $sales = SaleHold::where('user_id', Auth::user()->id)->where('clinic_id', Auth::user()->clinic_id)->orderBy('created_at','desc')->get();
            return SaleHoldResource::collection($sales);
        }else{
            $sales = SaleHold::where('user_id', Auth::user()->id)->where('branch_id', Auth::user()->branch_id)->orderBy('created_at','desc')->get();
            return SaleHoldResource::collection($sales);
        }
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
            'identification' => 'required'
        ]);
        $check = SaleCart::where('hold',0)->where('user_id',Auth::user()->id)->get();
        if (count($check)>0){
            $salehold = SaleHold::create([
                'identification' => $request->identification,
                'user_id' => Auth::user()->id,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
            ]);
            SaleCart::where('hold',0)->where('user_id',Auth::user()->id)->update(['hold'=>1, 'holdId'=>$salehold->id]);
        }
        else{
            return  response(['message'=>'There are no Items to hold'],201);

        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SaleHold  $saleHold
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = SaleHold::find($id);
        return new SaleHoldResource($order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SaleHold  $saleHold
     * @return \Illuminate\Http\Response
     */
    public function edit(SaleHold $saleHold)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SaleHold  $saleHold
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $checkCart = SaleCart::where('user_id', Auth::user()->id)->where('hold',0)->get();
        if($checkCart->count()>0){
            return response(["message"=>"You have unprocessed order in the cart"],201);
        }
        SaleHold::find($id)->delete();
        SaleCart::where('holdId',$id)->update(['hold'=>0, 'holdId'=>null]);
        return response(["message"=>"Sale has been successfully unheld"],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SaleHold  $saleHold
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SaleHold::find($id)->delete();
        SaleCart::where('holdId',$id)->delete();
    }
}
