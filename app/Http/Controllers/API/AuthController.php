<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function signup(Request $request){
        $user=User::create([
            'phone'=>$request->phone,
            'name'=>$request->name,
            'image'=>$request->image
        ]);
        return response()->json([
            'status'=>200,
            'phone'=>$user,
            'message'=>"User Registered"
            
        ]);
    }
    public function signin(Request $request){
        $user = User::where('phone', $request->phone)->first();
 
        if (!$user) {
            return response()->json([
                'status'=>'401',
                'message' =>'Invalid credentials'
            ]);
        }
        else{
            return response()->json([
                'status'=>'200',
                'username'=> $user,
                'message' => 'Logged In Successfully'
            ]);
        }
    }
    public function user_exist(Request $request){
        $user = User::where('phone', $request->phone)->first();
 
        if (!$user) {
            return response()->json([
                'status'=>'200',
                'message' =>'user not found'
            ]);
        }
        else{
            return response()->json([
                'status'=>'401',
                'username'=> $user,
                'message' => 'user already exists'
            ]);
        }
    }
}
