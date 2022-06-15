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
            if($request->phone != ""){
                $user = User::where('phone', $request->phone)->first();
                if(isset($user->user_id)){
                    $OTP = random_int(1000, 9999);
                    $account_sid = env('TWILIO_SID');
                    $account_token = env('TWILIO_TOKEN');
                    $number = env('TWILIO_FROM');
                    $client = new Client($account_sid,$account_token);
                    $client->messages->create($request->phone,[
                        'from'=>$number,
                        'body'=>"here is OTP ".$OTP
                    ]);

                User::where('user_id',$user->user_id)->update([
                    'OTP' => $OTP
                ]);
                    return response()->json([
                        "status"=> "200",
                        "user"=>$user->JWT,
                        "message"=>"user exists"
                    ]);
                }else{
                $OTP = random_int(1000, 9999);
                $account_sid = env('TWILIO_SID');
                $account_token = env('TWILIO_TOKEN');
                $number = env('TWILIO_FROM');
                $client = new Client($account_sid,$account_token);
                $client->messages->create($request->phone,[
                    'from'=>$number,
                    'body'=>"here is OTP ".$OTP
                ]);
                
                $user=User::create([
                    'phone'=>$request->phone,
                    'OTP'=>$OTP
                ]);
                $profile = User::where('phone', $request->phone)->first();
                $token = $user->createToken($profile->phone.$profile->user_id.'_Token')->plainTextToken;
                User::where('user_id',$profile->user_id)->update([
                    'JWT' => $token
                ]);
                Profile::create([
                    'phone'=>$request->phone,
                    'user_id'=>$profile->user_id
                ]);
            
                // Image_upload::create([
                //     'image_url'=>'https://res.cloudinary.com/alex-project/image/upload/v1653127578/Images/r8fb9weiqlitnz8fddtg.png',
                //     'user_id'=>$profile->user_id
                // ]);
                return response()->json([
                    'status'=>200,
                    'JWT'=>$token,
                    'message'=>"User Registered and OTP sent"
                    
                ]);
                }
            }else{
                return response()->json([
                    'status'=>403,
                    'message'=> "number can't be empty"
                ]);
            }
        } catch(\Exception $e){
            return response()->json([
                'status'=>400,
                'message'=> $e->getMessage()
            ]);
        }

    }
    public function allUsers(){
        try {
            $users = DB::table('users')
        ->join('profiles', 'users.user_id', '=', 'profiles.user_id')
        ->join('image_uploads', 'users.user_id', '=', 'image_uploads.user_id')
        ->select('users.*', 'profiles.name','profiles.phone','image_uploads.image_url')
        ->get(); 
        
        if($users != ""){
            return response()->json([
                'status'=>200,
                'users' =>$users
            ],200);
        }else{
            return response()->json([
                'status'=>404,
                'users' =>"no user found"
            ],404);
        }
        } catch(\Exception $e){
            return response()->json([
                'status'=>400,
                'message'=> $e->getMessage()
            ],400);
        }
}



    public function head(){
        try {
           $head= $_SERVER['HTTP_AUTHORIZATION'];
        return response()->json([
                'status'=>200,
                'message'=> $head
            ]);
        } catch(\Exception $e){
            return response()->json([
                'status'=>400,
                'message'=> $e->getMessage()
            ]);
        }
}
    // public function user_exist(Request $request){
    //         try {
    //         $user = User::where('phone', $request->phone)->first();
    
    //         if (!$user) {
    //             return response()->json([
    //                 'status'=>'200',
    //                 'message' =>'user not found'
    //             ]);
    //         }
    //         else{
    //             return response()->json([
    //                 'status'=>'401',
    //                 'username'=> $user,
    //                 'message' => 'user already exists'
    //             ]);
    //         }
    //     } catch(\Exception $e){
    //         return $e->getMessage();
    //     }
    // }
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