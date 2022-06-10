<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alarm;
use App\Models\User;
use App\Models\Profile;
use App\Models\AlarmTone;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class AlarmController extends Controller
{
    public function setAlarm(Request $request){
        try{
            $user = User::where('JWT', $request->JWT)->first();
            if(isset($user->user_id)){
            $profile = Profile::where('user_id', $user->user_id)->first();
            $uploadedFileUrl = Cloudinary::uploadFile($request->file('file')->getRealPath(),['folder'=> 'Alarm/Alarm_files'])->getSecurePath();
            $uploadedRingtone= Cloudinary::uploadFile($request->file('ringtone')->getRealPath(),['folder'=> 'Alarm/Alarm_ringtone'])->getSecurePath();
                Alarm::create([
                    "alarm_time"=>$request->time,
                    "user"=>$profile->name,
                    "user_id"=>$user->user_id,
                    "file"=>$uploadedFileUrl,
                    "ringtone"=>$uploadedRingtone
                ]); 
                return response()->json([
                    "status"=>"200",
                    "message"=>"Alarm has been set"
                ]);
            }else{
                return response()->json([
                    "status"=>"401",
                    "message"=>"Invalid Token"
                ]);
            }
        } catch(\Exception $e){
            return response()->json([
                'status'=>403,
                'message'=> $e->getMessage()
            ]);
        }
    }
    public function getAlarm(Request $request){
        try{
            $user = User::where('JWT', $request->JWT)->first();
            if(isset($user->user_id)){
            $alarm=Alarm::where('user_id', $user->user_id)->get();

                return response()->json([
                    "status"=>"200",
                    "message"=>$alarm
                ]);
            }else{
                return response()->json([
                    "status"=>"401",
                    "message"=>"Invalid Token"
                ]);
            }
        } catch(\Exception $e){
            return response()->json([
                'status'=>403,
                'message'=> $e->getMessage()
            ]);
        }
    }
    public function AllRingtone(){
        try{
            $alarm=AlarmTone::all();

                return response()->json([
                    "status"=>"200",
                    "ringtone"=>$alarm
                ]);
            
        } catch(\Exception $e){
            return response()->json([
                'status'=>403,
                'message'=> $e->getMessage()
            ]);
        }
    }
}
