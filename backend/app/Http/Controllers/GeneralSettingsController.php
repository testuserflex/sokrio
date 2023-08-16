<?php

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use App\Models\ProductCategory;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class GeneralSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->branch_id == 0){
            $setting = GeneralSetting::where('business_id', Auth::user()->business_id)->first();
        }else{
            $setting = GeneralSetting::where('branch_id', Auth::user()->branch_id)->first();
        }
        return $setting;
    }


    public function fetch_message()
    {

        $setting = GeneralSetting::where('branch_id', Auth::user()->branch_id)->first('customer_message');
        return $setting;
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
        $setting = GeneralSetting::where('branch_id', Auth::user()->branch_id)->first();
        $setting->customer_message = $request->message;
        $setting->save();
        return response(['message'=>"Customer appreciation message has been successfully set"],200);
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
        if($request->column == 'confirm_readyorders' && $request->status == 1){
            $catcheck = ProductCategory::where('name', '=', 'Kitchen-Pdts')
                ->where('business_id', Auth::user()->business_id)->where('status',1)->get();
            if ($catcheck->count()>0) {
                GeneralSetting::where('id',$request->id)->update([$request->column=>$request->status]);
            }else{
                ProductCategory::create([
                    'name' => 'Kitchen-Pdts',
                    'user_id' => Auth::user()->id,
                    'branch_id' => Auth::user()->branch_id,
                    'business_id' => Auth::user()->business_id,
                ]);

                $action = " added a new product category ".$request->name;
                app('App\Http\Controllers\FunctionController')->log($action,Log::Stock);
                GeneralSetting::where('id',$request->id)->update([$request->column=>$request->status]);
            }
        }else{
            GeneralSetting::where('id',$request->id)->update([$request->column=>$request->status]);

            $action = "Updated general setting $request->column";
            app('App\Http\Controllers\FunctionController')->log($action,Log::Stock);
        }

        return response(['message'=>"General setting updated successfully"],200);
//

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
