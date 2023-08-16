<?php

namespace App\Http\Controllers;

use App\Http\Resources\LogResource;
use App\Http\Resources\RoleResource;
use App\Models\Log;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->branch_id==0){
            $roles = Permission::where('business_id', Auth::user()->business_id)->get();
        }
        else{
            $roles = Permission::where('branch_id', Auth::user()->branch_id)->get();
        }
        return RoleResource::collection($roles);
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
        ]);

        $rolecheck = Permission::where('role',$request->name)->where('branch_id', Auth::user()->branch_id)->first();
        if ($rolecheck) {
            if($rolecheck->status!=1){
                return response(['message'=>"This User Group already exists, Activate Instead"],201);
            }
            else{
                return response(['message'=>"This User Group already exists"],201);
            }
        }
        else {
            Permission::create([
                'role' => $request->name,
                'user_id' => Auth::user()->id,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
            ]);

            $action = " added a new user group ".$request->name." successfully";
            Log::create([
                'activity' => $action,
                'category' => 1,
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
            'name' => 'required'
        ]);
        $role = Permission::find($id);
        $role->role = $request->name;
        $role->save();
        $action = "Edited user group ".$request->name." successfully";
        Log::create([
            'activity' => $action,
            'category' => 1,
            'user_id' => Auth::user()->id,
            'branch_id' => Auth::user()->branch_id,
            'business_id' => Auth::user()->business_id,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Permission::find($id);
        if ($role->status == 0){
            $role->status=1;
            $action = "Activated User group $role->name";
        }
        else{
            $check = User::where('role',$id)->get();
            if($check->count() > 0){
                $role->status=0;
                $action = "Deactivated User group $role->name";
            }
            else{
                $role->delete();
                $action = "Deleted a User group $role->name";
            }
        }

        Log::create([
            'activity' => $action,
            'category' => 1,
            'user_id' => Auth::user()->id,
            'branch_id' => Auth::user()->branch_id,
            'business_id' => Auth::user()->business_id,
        ]);
    }

    public function audits(){
        if(Auth::user()->branch_id==0){
            $logs = Log::where('business_id', Auth::user()->business_id)->orderBy("created_at", "desc")->limit(2000)->get();
        }
        else{
            $logs = Log::where('branch_id', Auth::user()->branch_id)->orderBy("created_at", "desc")->limit(2000)->get();
        }
        return LogResource::collection($logs);
    }
}
