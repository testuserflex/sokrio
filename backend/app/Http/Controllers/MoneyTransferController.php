<?php

namespace App\Http\Controllers;

use App\Http\Resources\MoneyTransferResource;
use App\Models\Log;
use App\Models\MoneyTransfer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MoneyTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->branch_id==0){
            $transfers = MoneyTransfer::where('business_id', Auth::user()->business_id)->orderBy("created_at", "desc")->get();
        }
        else{
            $transfers = MoneyTransfer::where('branch_id', Auth::user()->branch_id)->orderBy("created_at", "desc")->get();
        }
        return MoneyTransferResource::collection($transfers);
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
            'amount' => 'required',
            'source' => 'required|integer',
            'destination' => 'required|integer',

        ]);
        $transfer = MoneyTransfer::create([
            'amount' => str_replace(' ', '', $request->amount),
            'date' => $request->date ?? date('Y-m-d'),
            'from' => $request->source,
            'to' => $request->destination,
            'reason' => $request->reason,
//            'refno' => $request->refno,
            'madeBy' => $request->madeby,
            'user_id' => Auth::user()->id,
            'branch_id' => Auth::user()->branch_id,
            'business_id' => Auth::user()->business_id,
        ]);

        // Insert into transactions
        app('App\Http\Controllers\FunctionController')->transaction(8,$transfer->id,$transfer->amount,0,$transfer->to,"Recorded a Money Transfer",$transfer->date);
        app('App\Http\Controllers\FunctionController')->transaction(8,$transfer->id,0,$transfer->amount,$transfer->from,"Recorded a Money Transfer",$transfer->date);
        // Capture log
        $action = "Recorde a money Transfer of amount ".$transfer->amount." made by ".$transfer->madeBy." for ".$transfer->reason;
        app('App\Http\Controllers\FunctionController')->log($action,Log::Finance);
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
        $transfer = MoneyTransfer::find($id);
        $transfer->amount = str_replace(",","",$request->amount);
        $transfer->from = $request->source;
        $transfer->refno = $request->refno;
        $transfer->to = $request->destination;
        $transfer->date = $request->date;
        $transfer->reason = $request->reason;
        $transfer->madeBy = $request->madeby;
        $transfer->save();

        // update transactions table
        $transcn = Transaction::where('category',8)->where('tId',$id)->where('branch_id',Auth::user()->branch_id)->first();
        $transcn->amount_in = str_replace(",","",$request->amount);
        $transcn->mode = $request->to;
        $transcn->save();

        $transcn1 = Transaction::where('category',8)->where('tId',$id)->where('branch_id',Auth::user()->branch_id)->orderBy('created_at','desc')->first();
        $transcn1->amount_out = str_replace(",","",$request->amount);
        $transcn1->mode = $request->from;
        $transcn1->save();
        // record log
        $action = "updated a money transfer of amount ".$transfer->amount." given to ".$transfer->givenTo." for ".$transfer->reason;
        app('App\Http\Controllers\FunctionController')->log($action,Log::Finance);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $transfer = MoneyTransfer::find($id);
        $transfer->delete();
        Transaction::where('category',8)->where('tId',$id)->where('branch_id',Auth::user()->branch_id)->delete();
        $action = "deleted a money transfer record of amount ".$transfer->amount;
        app('App\Http\Controllers\FunctionController')->log($action,Log::Finance);

    }
}
