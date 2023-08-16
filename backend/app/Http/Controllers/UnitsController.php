<?php

namespace App\Http\Controllers;

use App\Http\Resources\UnitResource;
use App\Models\Unit;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->branch_id==0){
            $units = Unit::where('business_id', Auth::user()->business_id)->where('status', 1)->get();
        }
        else{
            $units = Unit::where('branch_id', Auth::user()->branch_id)->where('business_id', Auth::user()->business_id)->where('status', 1)->get();
        }
        return UnitResource::collection($units);
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
            'symbol' => 'required',
            'name' => 'required',
        ]);

        $unitcheck = Unit::where('name', '=', $request->name)->orWhere('symbol', '=', $request->symbol)
        ->where('branch_id', Auth::user()->branch_id)->get();
        if ($unitcheck->count()>0) {
        }
        else {
            Unit::create([
                'name' => $request->name,
                'symbol' => $request->symbol,
                'user_id' => Auth::user()->id,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
            ]);

            $action = " added a new unit ".$request->name." with symbol ".$request->symbol;
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
        $unit = Unit::find($id);
        $unit->name = $request->name;
        $unit->symbol = $request->symbol;
        $unit->save();

        $action = " updated unit ".$request->name." details successfully";
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
        $unit = Unit::find($id);
        $unit->status = 0;
        $unit->save();
        $action = "deleted ".$unit->name." successfully";
        app('App\Http\Controllers\FunctionController')->log($action,Log::Stock);
    }
}
