<?php

namespace App\Http\Controllers;
use App\Models\Log;
use App\Models\PaymentOption;
use App\Http\Resources\PaymentOptionResource;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->branch_id==0){
            $option = PaymentOption::with(['user','branch'])->where('business_id',Auth::user()->business_id)->where('status',1)->get();
        }
        else{
            $option = PaymentOption::with(['user','branch'])->where('branch_id',Auth::user()->branch_id)->where('status',1)->get();
        }
        return PaymentOptionResource::collection($option);
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
            'type' => 'required',
            'type_name' => 'required',
        ]);

        $optioncheck = PaymentOption::where('name', $request->name)->where('type', $request->type)->where('type_name', $request->type_name)
        ->where('branch_id', Auth::user()->branch_id)->get();
        if ($optioncheck->count()>0) {
            return response(['message'=>"This Payment Option has already been registered"],202);
        }
        else {
            $popt = PaymentOption::create([
                'name' => $request->name,
                'balance' => str_replace(",","",$request->balance) ?? 0,
                'type' => $request->type,
                'type_name' => $request->type_name,
                'account_number' => $request->account_number,
                'user_id' => Auth::user()->id,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
            ]);

            if($popt->balance!=0){
                // insert into transactions
            app('App\Http\Controllers\FunctionController')->transaction(10,$popt->id,$popt->balance,0,$popt->id,"Opening Balance");
            }
            $action = " added a new payment option ".$request->name." of type ".$request->type;
            app('App\Http\Controllers\FunctionController')->log($action,Log::Management);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function statement(Request $request)
    {
        if(Auth::user()->branch_id==0){
            return Transaction::with("modex")->where('mode',$request->id)->whereBetween("date", [$request->datefrom, $request->dateto])->where('business_id',Auth::user()->business_id)->orderBy("date", "desc")->get();
        }else{
            return Transaction::with("modex")->where('mode',$request->id)->whereBetween("date", [$request->datefrom, $request->dateto])->where('branch_id',Auth::user()->branch_id)->orderBy("date", "desc")->get();
        }
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
        $option = PaymentOption::find($id);
        $option->name = $request->name;
        $option->type = $request->type;
        $option->type_name = $request->type_name;
        $option->account_number = $request->account_number;
        $option->save();

        $action = " updated payment option ".$request->name." of type ".$request->type_name." details";
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
        $option = PaymentOption::find($id);
        if($option->is_default==1){
            return response(['message'=>"The default payment option can not be deleted"]);
        }
        else{
            $option->status = 0;
            $option->save();
            $action = "deleted a payment option".$option->name;
            app('App\Http\Controllers\FunctionController')->log($action,Log::Management);
        }
    }

    public function changeDefault(Request $request){
        $id = $request->id;
        PaymentOption::where('branch_id',Auth::user()->branch_id)->update(['is_default'=>0]);
        PaymentOption::where('branch_id',Auth::user()->branch_id)->where('id',$id)->update(['is_default'=>1]);

        $action = "changed default payment option to ".$request->name;
        app('App\Http\Controllers\FunctionController')->log($action,Log::Management);
    }
}
