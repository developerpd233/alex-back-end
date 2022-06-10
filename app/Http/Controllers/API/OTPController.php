<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\Models\User;


class OTPController extends Controller
{
    public function verifyOtp(Request $request){
        try {

           $user = User::where('JWT', $request->JWT)->first();
            if(isset($user->user_id)){

                $OTP = User::where('OTP', $request->OTP)->first();
                if(isset($OTP)){
                    return response()->json([
                        'status'=>200,
                        'message' =>"Valid OTP"
                    ]);
                }else{
                    return response()->json([
                        'status'=>403,
                        'message' =>"Invalid OTP"
                    ]);
                }
              
            }else{
                return response()->json([
                    'status'=>401,
                    'message' =>"Invalid Token"
                ]);
            }
        } catch(\Exception $e){
            return response()->json([
                'status'=>400,
                'message'=> $e->getMessage()
            ]);
        }
        
    }
    public function resendOTP(Request $request){
        try {
            $user = User::where('JWT', $request->JWT)->first();
            if(isset($user->user_id)){
                $OTP = random_int(1000, 9999);
                $account_sid = env('TWILIO_SID');
                $account_token = env('TWILIO_TOKEN');
                $number = env('TWILIO_FROM');
        
                $client = new Client($account_sid,$account_token);
                $client->messages->create($user->phone,[
                    'from'=>$number,
                    'body'=>"here is OTP ".$OTP
                ]);
                User::where('user_id',$user->user_id)->update([
                    'OTP' => $OTP
                ]);
                return response()->json([
                    'status'=>200,
                    'message' =>"OTP resend"
                ]);
            }else{
                return response()->json([
                    'status'=>401,
                    'message' =>"Invalid token"
                ]);
            }
        } catch(\Exception $e){
            return response()->json([
                'status'=>400,
                'message'=> $e->getMessage()
            ]);
        }
        
    }
    public function authOtp(Request $request){
        try {

            $randomNumber = random_int(1000, 9999);
            $account_sid = env('TWILIO_SID');
            $account_token = env('TWILIO_TOKEN');
            $number = env('TWILIO_FROM');
    
            $client = new Client($account_sid,$account_token);
            $client->messages->create($request->phone,[
                'from'=>$number,
                'body'=>"here is OTP ".$randomNumber
            ]);
            return response()->json([
                'status'=>200,
                'message' =>"Message sent"
            ]);
    
        } catch(\Exception $e){
            return response()->json([
                'status'=>400,
                'message'=> $e->getMessage()
            ]);
        }
        
    }
    
}
