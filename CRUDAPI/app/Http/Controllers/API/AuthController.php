<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function signup(Request $request){
        $validateuser= Validator::make(
            $request->all(),
            [
                'name'=>'required',
                'email'=>'required|email|unique:users,email',
                'password'=>'required',
            ]
        );

        if($validateuser->fails()){
            return response()->json([
                'status'=>false,
                'message'=>'Validation Error',
                'errors'=>$validateuser->errors()->all()
            ],401);
        }

        $user=User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> $request->password,
        ]);

        return response()->json([
            'status'=>true,
            'message'=>'User Created Successfully',
            'user'=>$user,
        ],200);
    }
    public function login(Request $request){
        $validateuser= Validator::make(
            $request->all(),
            [
                'email'=>'required|email',
                'password'=>'required',
            ]
        );
        if($validateuser->fails()){
            return response()->json([
                'status'=>false,
                'message'=>'Authentication Fails',
                'errors'=>$validateuser->errors()->all()
            ],404);
        }
        if(Auth::attempt(['email'=> $request->email,'password'=> $request->password])){
            $authuser=Auth::user();
            return response()->json([
                'status'=>true,
                'message'=>'User Logged Successfully',
                'token'=>$authuser->createToken("API Token")->plainTextToken,
                'token_type'=>'bearer'
            ],200);
        }
        else{
            return response()->json([
                'status'=>false,
                'message'=>'Email and Password does not match',
            ],401);
        }
    }
    public function logout(Request $request){
        $user=$request->user();
        $user->tokens()->delete();
        return response()->json([
            'status'=>true,
            'user'=>$user,
            'message'=>'You logged out Successfully',
        ],200);
    }
    
}
