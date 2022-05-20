<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;

use Twilio\Rest\Client;
use Carbon\Carbon;


class AuthController extends Controller
{
    public function signup(Request $request){

        try {
            $user_rand = random_int(100, 999);
            $user_id=$request->phone.$user_rand;
            $profile=Profile::create([
                'phone'=>$request->phone,
                'user_id'=>$user_id
            ]);

            $token = $profile->createToken('token')->plainTextToken;
            
            $todayDate = Carbon::now()->timezone('PKT')->format('H:i:m');
            $OTP = random_int(1000, 9999);
            $account_sid = env('TWILIO_SID');
            $account_token = env('TWILIO_TOKEN');
            $number = env('TWILIO_FROM');
            $client = new Client($account_sid,$account_token);
            $client->messages->create('+92'.$request->phone,[
                'from'=>$number,
                'body'=>"here is OTP ".$OTP." ".$todayDate
            ]);
            
            $user=User::create([
                'phone'=>$request->phone,
                'JWT'=>$token,
                'user_id'=>$user_id,
                'OTP'=>$OTP
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
    public function authOtp(Request $request){
        try {

            $todayDate = Carbon::now()->timezone('PKT')->format('H:i:m');
            // $randomNumber = random_int(1000, 9999);
            $account_sid = env('TWILIO_SID');
            $account_token = env('TWILIO_TOKEN');
            $number = env('TWILIO_FROM');
    
            $client = new Client($account_sid,$account_token);
            $client->messages->create('+92'.$request->phone,[
                'from'=>$number,
                'body'=>"here is OTP ".$randomNumber." ".$todayDate
            ]);
            // $token = $user->createToken($user->email.'_Token')->plainTextToken;
            return response()->json([
                'status'=>'200',
                'message' =>"Message sent"
            ]);
    
        } catch(\Exception $e){
            return $e->getMessage();
        }
        
    }
}
