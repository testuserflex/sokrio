<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use App\Models\Log;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->branch_id==0){
            $transfers = Expense::where('business_id', Auth::user()->business_id)->orderBy("created_at", "desc")->get();
        }
        else{
            $transfers = Expense::where('branch_id', Auth::user()->branch_id)->orderBy("created_at", "desc")->get();
        }
        return ExpenseResource::collection($transfers);
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
        return $request;
        $request->validate([
            'amount' => 'required',
            'mode' => 'required',
            'category' => 'required',
        ]);
        $expense = Expense::create([
            'amount' => str_replace(' ', '', $request->amount),
            'mode_id' => $request->mode,
            'date' => $request->date ?? date('Y-m-d'),
            'category_id' => $request->category,
            'description' => $request->description,
            'user_id' => Auth::user()->id,
            'branch_id' => Auth::user()->branch_id,
            'business_id' => Auth::user()->business_id,
        ]);

        // Insert into transactions
        app('App\Http\Controllers\FunctionController')->transaction(2,$expense->id,0,$expense->amount,$expense->mode_id,"Recorded an Expense",$expense->date);
        // Capture log
        $action = "recorded an expense of amount ".$expense->amount." spent on ".$expense->date." for ".$expense->description;
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
        $expense = Expense::find($id);
        $expense->amount = str_replace(",","",$request->amount);
        $expense->mode_id = $request->mode;
        $expense->category_id = $request->category;
        $expense->date = $request->date;
        $expense->description = $request->description;
        $expense->save();

        // update transactions table
        Transaction::where('category',2)->where('tId',$id)->where('branch_id',Auth::user()->branch_id)
        ->update(['amount_out'=>str_replace(",","",$request->amount),'mode'=>$request->mode]);

        // record log
        $action = "updated an expense of amount ".$expense->amount." recorded on ".$expense->date." for ".$expense->description;
        app('App\Http\Controllers\FunctionController')->log($action,Log::Finance);
    }

    // Purchase::truncate();

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $expense = Expense::find($id);
        $expense->delete();
        Transaction::where('category',2)->where('tId',$id)->where('branch_id',Auth::user()->branch_id)->delete();
        $action = "deleted an expense of amount ".$expense->amount." recorded on ".$expense->date." for ".$expense->description;
        app('App\Http\Controllers\FunctionController')->log($action,Log::Finance);
    }
}
