<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\User;
use App\Models\Alarm;
use App\Models\Image_upload;

class ProfileController extends Controller
{
    public function get_profile(){
        try { 
            $JWT= $_SERVER['HTTP_AUTHORIZATION'];
            if($JWT != ""){
           $user = User::where('JWT', $JWT)->first();
            if(isset($user->user_id)){

                $profile = Profile::where('user_id', $user->user_id)->get();
                $image=Image_upload::where('user_id', $user->user_id)->get();
                return response()->json([
                    'status'=>200,
                    'profile'=>$profile,
                    'image'=>$image,
                    'message' =>"Requested profile found"
                ]);
            }else{
                return response()->json([
                    'status'=>403,
                    'message' =>"Requested profile not found"
                ]);
            }
        }else{
            return response()->json([
                "status"=>403,
                "message"=>"Token can't be empty"
            ]);
        }
        } catch(\Exception $e){
            return response()->json([
                'status'=>400,
                'message'=> $e->getMessage()
            ]);
        }
        
    }


    public function update_profile(Request $request){
        try {
            $JWT= $_SERVER['HTTP_AUTHORIZATION'];
            if($JWT != ""){
           $user = User::where('JWT', $JWT)->first();
            if(isset($user->user_id)){

                Profile::where('user_id', $user->user_id)->update([
                    'name' => $request->name,
                    'about' => $request->about
                ]);
                Alarm::where('user_id', $user->user_id)->update([
                    'user' => $request->name
                ]);
                return response()->json([
                    'status'=>200,
                    'message' =>"Profile Updated successfully"
                ]);
            }else{
                return response()->json([
                    'status'=>403,
                    'message' =>"Requested profile not found"
                ]);
            }
        }else{
            return response()->json([
                "status"=>403,
                "message"=>"Token can't be empty"
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
