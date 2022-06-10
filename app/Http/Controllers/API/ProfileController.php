<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\User;
use App\Models\Alarm;


class ProfileController extends Controller
{
    public function get_profile(Request $request){
        try { 
            $user = User::where('JWT', $request->JWT)->first();
            if(isset($user->user_id)){

                $profile = Profile::where('user_id', $user->user_id)->get();
                return response()->json([
                    'status'=>'200',
                    'profile'=>$profile,
                    'message' =>"Requested profile found"
                ]);
            }else{
                return response()->json([
                    'status'=>'401',
                    'message' =>"Requested profile not found"
                ]);
            }
          
        } catch(\Exception $e){
            return response()->json([
                'status'=>403,
                'message'=> $e->getMessage()
            ]);
        }
        
    }
    public function update_profile(Request $request){
        try {
            $user = User::where('JWT', $request->JWT)->first();
            if(isset($user->user_id)){

                Profile::where('user_id', $user->user_id)->update([
                    'name' => $request->name,
                    'about' => $request->about
                ]);
                Alarm::where('user_id', $user->user_id)->update([
                    'user' => $request->name
                ]);
                return response()->json([
                    'status'=>'200',
                    'message' =>"Profile Updated successfully"
                ]);
            }else{
                return response()->json([
                    'status'=>'401',
                    'message' =>"Requested profile not found"
                ]);
            }
        } catch(\Exception $e){
            return response()->json([
                'status'=>403,
                'message'=> $e->getMessage()
            ]);
        }
        
    }
}
