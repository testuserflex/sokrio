<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExpenseCategoryResource;
use App\Models\ExpenseCategory;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->branch_id==0){
            $transfers = ExpenseCategory::where('business_id', Auth::user()->business_id)->where('status', 1)->get();
        }
        else{
            $transfers = ExpenseCategory::where('branch_id', Auth::user()->branch_id)->where('status', 1)->get();
        }
        return ExpenseCategoryResource::collection($transfers);
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
            'name' => 'required'
        ]);

        $checkcat = ExpenseCategory::where('name',$request->name)->where('branch_id',Auth::user()->branch_id)->get();
        if(count($checkcat)>0){
            return response(['message'=>"Expense category with name $request->name already exists"],202);
        }
        else{
            ExpenseCategory::create([
                'name' => $request->name,
                'user_id' => Auth::user()->id,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
            ]);
        }

        // Capture log
        $action = " added a new expense category ".$request->name;
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
        $category = ExpenseCategory::find($id);
        $category->name = $request->new_name;
        $category->save();

        $action = " updated expense category ".$request->new_name." details";
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
        $category = ExpenseCategory::find($id);
        $category->status = 0;
        $category->save();
        $action = "deleted an expense category ".$category->name;
        app('App\Http\Controllers\FunctionController')->log($action,Log::Finance);
    }
}
