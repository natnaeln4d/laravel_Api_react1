<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;

class Authcontroller extends Controller
{
  use HasApiTokens, HasFactory, Notifiable;
    /**
     * create user
    *@param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
     */
    // public function __construct() {
    //     $this->middleware('auth:api', ['except' => ['login', 'register']]);
    // }
    public function createuser(Request $request)
    {

        try{
            $uservalidator=Validator::make($request->all(),[
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required'],
            ]);
            if($uservalidator->fails()){
                return response()->json([
                    "status"=>false,
                    "message"=>"valitor error",
                    "error"=>$uservalidator->errors()
                ],401);
            }
            $user=User::create(
                [
                    'name'=>$request->name,
                    'email'=>$request->email,
                    'password'=>Hash::make($request->password)
                ]
                );
            return response()->json([
                "status"=>true,
                "message"=>"user created succesfully",
                "token"=>$user->createtoken("user_token")->plainTextToken
            ] ,200);
        }
        catch(\Throwable $th){
            return response()->json([
                "status"=>true,
                "message"=>$th->getMessage()
            ],500);

        }


    }
    public function loginuser(Request $request)
    {
        try{
            $uservalidator=Validator::make($request->all(),[
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required']
              ]);
              if(!Auth::attempt($request->only('email','password'))){

                return response()->json([
                    "status"=>false,
                    "message"=>"Email or password error"
                ],401);
              }
        $user=User::where("email",$request->email)->first();
        return response()->json([
            "status"=>true,
            "message"=>"user logined succesfully",
            "token"=>$user->createtoken("API TOKEN")->plainTextToken
        ] ,200);

        }catch(\Throwable $th){
            return response()->json([
                "status"=>true,
                "message"=>$th->getMessage()
            ],500);

        }

    }
    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
}
