<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Carbon\Carbon;

class OTPController extends Controller
{
    //
    public function authOtp(Request $request){
        try {

            $todayDate = Carbon::now()->timezone('PKT')->format('H:i:m');
            $randomNumber = random_int(1000, 9999);
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
