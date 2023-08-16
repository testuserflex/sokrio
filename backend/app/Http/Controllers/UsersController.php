<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Http\Resources\AccessTimeResource;
use App\Jobs\EmailJob;
use App\Jobs\SendResetCodeJob;
use App\Jobs\BusinessCreatedEmailJob;
use App\Jobs\BusinessCreatedSmsJob;
use App\Jobs\SendResetCodeMessage;
use App\Mail\SendMail;
use App\Mail\SendResetCodeMail;
use App\Mail\SendBusinessCreatedMail;
use App\Models\Log;
use App\Models\Permission;
use App\Models\TempPassword;
use App\Models\User;
use App\Models\Branch;
use App\Models\Business;
use App\Models\AccessTime;
use App\Models\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->branch_id==0){
            $units = User::where('business_id', Auth::user()->business_id)->orderBy("created_at", "desc")->get();
        }
        else{
            $units = User::where('branch_id', Auth::user()->branch_id)->orderBy("created_at", "desc")->get();
        }
        return UserResource::collection($units);
    }

    public function fetchusers()
    {
        $users = User::where('user_id','!=', 0)->with('rolex', 'branch', 'business')->orderBy("created_at", "desc")->get();
        $businesses = Business::all();
        $branches = Branch::all();
        $roles = Permission::all();
        return view('users.users')->with(['roles' => $roles, 'branches'=>$branches, 'businesses'=>$businesses,'users'=>$users]);
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
            'username' => 'required|min:4',
            'name' => 'required',
            'contact' => 'required',
            'role' => 'required',
        ]);

        // check user name
        $usernameCheck = User::where('username',$request->username)->get();
        if ($usernameCheck->count()>0) {
            return response(["message"=>"Username ".$request->username." already taken"],201);
        }
        if($request->email != ""){
            $mailCheck = User::where('email',$request->email)->where('branch_id', Auth::user()->branch_id)->get();
            if ($mailCheck->count()>0) {
                return response(["message"=>"Email ".$request->email." already Exists"],201);
            }
        }
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'contact' => $request->contact,
                'role' => $request->role,
                'password' => bcrypt($request->password),
                'user_id' => Auth::user()->id,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
            ]);

            //TEMP PASSWORD
            TempPassword::create([
                'user_id' => $user->id,
                'business_id' => Auth::user()->business_id,
                'branch_id' => Auth::user()->branch_id,
                'temp_password' => $request->password,
            ]);

            $action = " added a new user ".$request->name." with phone number ".$request->contact;
            Log::create([
                'activity' => $action,
                'category' => 1,
                'user_id' => Auth::user()->id,
                'branch_id' => Auth::user()->branch_id,
                'business_id' => Auth::user()->business_id,
            ]);

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

    public function login(Request $request)
    {
        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response(['message' => 'The credentials provided do not match any user records.'], 202);
        }

        if ($user->status == 2) {
            return response(['message' => 'The provided credentials are for a suspended account. Contact your Admin'], 202);
        }

        if ($user->status == 0) {
            return response(['message' => 'The provided credentials are for an abandoned account. Contact your Admin'], 202);
        }
        $response = response(['token' => $user->createToken('My-token')->plainTextToken,'user'=>new UserResource($user)], 200);
        return $response;

    }

    public function logout()
    {
         Auth::user()->currentAccessToken()->delete();
         return 'logged out success';
    }
}
