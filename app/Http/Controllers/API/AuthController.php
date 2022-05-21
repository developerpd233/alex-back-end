<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use App\Models\Image_upload;
use Twilio\Rest\Client;


class AuthController extends Controller
{
    public function signup(Request $request){

        try {
            $OTP = random_int(1000, 9999);
            $account_sid = env('TWILIO_SID');
            $account_token = env('TWILIO_TOKEN');
            $number = env('TWILIO_FROM');
            $client = new Client($account_sid,$account_token);
            $client->messages->create($request->phone,[
                'from'=>$number,
                'body'=>"here is OTP ".$OTP
            ]);
            $user_rand = random_int(100, 999);
            $user_id=$user_rand.$request->phone.$user_rand;
            $profile=Profile::create([
                'phone'=>$request->phone,
                'user_id'=>$user_id
            ]);

            $token = $profile->createToken('token')->plainTextToken;
            
            $user=User::create([
                'phone'=>$request->phone,
                'JWT'=>$token,
                'user_id'=>$user_id,
                'OTP'=>$OTP
            ]);
            
            $user=Image_upload::create([
                'image_url'=>'https://res.cloudinary.com/alex-project/image/upload/v1653127578/Images/r8fb9weiqlitnz8fddtg.png',
                'JWT'=>$token,
                'user_id'=>$user_id,
            ]);
            
            return response()->json([
                'status'=>200,
                'JWT'=>$token,
                'message'=>"User Registered and OTP sent"
                
            ]);
    
        } catch(\Exception $e){
            return $e->getMessage();
        }

        }

    public function user_exist(Request $request){
            try {
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
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }
}
// public function signin(Request $request){
    //     $user = User::where('phone', $request->phone)->first();
 
    //     if (!$user) {
    //         return response()->json([
    //             'status'=>'401',
    //             'message' =>'Invalid credentials'
    //         ]);
    //     }
    //     else{
    //         return response()->json([
    //             'status'=>'200',
    //             'username'=> $user,
    //             'message' => 'Logged In Successfully'
    //         ]);
    //     }
    // }