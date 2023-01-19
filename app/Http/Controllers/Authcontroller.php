<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
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
    public function foregotpwd(Request $request)
    {
        $uservalidator=Validator::make($request->only($request->emai),[
           'email'=>['required','string','email','max:250','unique:user']
        ]);
       $status=Password::sendResetLink($uservalidator);
       if($status==Password::RESET_LINK_SENT){
        return [
            'status'=>__($status),
        ];
       }

       throw ValidationException::withMessages([
        'email'=>[trans($status)],
       ]);
    }
    public function reset(Request $request)
    {
        $credentials = request()->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);

        $reset_password_status = Password::reset($credentials, function ($user, $password) {
            $user->password = $password;
            $user->save();
        });

        if ($reset_password_status == Password::INVALID_TOKEN) {
            return response()->json(["message" => "Invalid token provided"], 400);
        }

        return response()->json(["message" => "Password has been successfully changed"]);
    }
    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
}
