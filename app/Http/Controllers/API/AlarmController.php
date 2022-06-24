<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alarm;
use App\Models\User;
use App\Models\Profile;
use App\Models\AlarmTone;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Storage;

class AlarmController extends Controller
{
    public function setAlarm(Request $request){
        try{
                $allowedImages = ['image/jpeg','image/gif','image/png'];
                $allowedVideos = ['video/mp4','video/mpeg'];
                $allowedtune = ['audio/mpeg'];

                $JWT= $_SERVER['HTTP_AUTHORIZATION'];
                if($JWT != ""){
                $user = User::where('JWT', $JWT)->first();
                if(isset($user->user_id)){

                $profile = Profile::where('user_id', $user->user_id)->first();
                // $uploadedFileUrl = Cloudinary::uploadFile($request->file('file')->getRealPath(),['folder'=> 'Alarm/Alarm_files'])->getSecurePath();
                // $uploadedRingtone= Cloudinary::uploadFile($request->file('ringtone')->getRealPath(),['folder'=> 'Alarm/Alarm_ringtone'])->getSecurePath();
                if($request->file('file') != ""){
                    if($request->file('ringtone') != ""){
                            if($request->time != ""){
                                $contentType = $request->file('file')->getClientMimeType();
                                $tuneType = $request->file('ringtone')->getClientMimeType();

                                if(in_array($contentType, $allowedImages) ){
                                    if(in_array($tuneType, $allowedtune) ){
                                    // ----------------------------image upload on AWS -------------------------------------------------
                                    $file= $request->file('file');
                                    $filename=$file->getClientOriginalName();
                                    $filename= time().'.'.$filename;
                                    $path =  Storage::disk('s3')->put('alarm/alarm_files', $request->file('file'));
                                    $url='https://alex-app.s3.amazonaws.com/'.$path;
    
                                    // ----------------------------ringtone upload on AWS -------------------------------------------------
                                    $ringtone= $request->file('ringtone');
                                    $tune=$ringtone->getClientOriginalName();
                                    $tune= time().'.'.$tune;
                                    $tune_path =  Storage::disk('s3')->put('alarm/alarm_ringtones', $request->file('ringtone'));
                                    $ringurl='https://alex-app.s3.amazonaws.com/'.$tune_path;
                                Alarm::create([
                                    "alarm_time"=>$request->time,
                                    "user"=>$profile->name,
                                    "user_id"=>$user->user_id,
                                    "file"=>$url,
                                    "type"=>"user alarm",
                                    "ringtone"=>$ringurl
                                ]); 
                                return response()->json([
                                    "status"=>200,
                                    "message"=>"Alarm has been set"
                                ]);
                                }else{
                                    return response()->json([
                                        "status"=>403,
                                        "message"=>"This ringtone format is not supported"
                                    ],403);
                                }}elseif(in_array($contentType, $allowedVideos) ){
                                $videofile= $request->file('file');
                                $videoname=$videofile->getClientOriginalName();
                                $videoname= time().'.'.$videoname;
                                $videopath =  Storage::disk('s3')->put('alarm/alarm_files', $request->file('file'));
                                $videourl='https://alex-app.s3.amazonaws.com/'.$videopath;

                                Alarm::create([
                                    "alarm_time"=>$request->time,
                                    "user"=>$profile->name,
                                    "user_id"=>$user->user_id,
                                    "type"=>"user alarm",
                                    "file"=>$videourl,
                                ]); 

                                    return response()->json([
                                        "status"=>200,
                                        "message"=>'Alarm has been set'
                                    ]);
                                }else{
                                return response()->json([
                                    "status"=>403,
                                    "message"=>'this file format is not supported'
                                ]);
                                }
                            }else{
                                return response()->json([
                                    "status"=>403,
                                    "message"=>'time should be provided'
                                ]);
                            }
                
                        }else{
                        return response()->json([
                        "status"=>403,
                        "message"=>'ringtone cannot be empty'
                        ]);
                        }
                }else{
                    return response()->json([
                    "status"=>403,
                    "message"=>'video or image should be provided'
                    ]);
                }
            }else{
            return response()->json([
                "status"=>401,
                "message"=>"Invalid Token"
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


        public function getAlarm(){
            try{
                $JWT= $_SERVER['HTTP_AUTHORIZATION'];
                if($JWT != ""){
                $user = User::where('JWT', $JWT)->first();
                    if(isset($user->user_id)){
                    $alarm=Alarm::where('user_id', $user->user_id)->get();

                        return response()->json([
                            "status"=>200,
                            "message"=>$alarm
                        ]);
                    }else{
                        return response()->json([
                            "status"=>401,
                            "message"=>"Invalid Token"
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

    
    public function AllRingtone(){
        try{
            
            $alarm=AlarmTone::all();

                return response()->json([
                    "status"=>200,
                    "ringtone"=>$alarm
                ]);
            
        } catch(\Exception $e){
            return response()->json([
                'status'=>400,
                'message'=> $e->getMessage()
            ]);
        }
    }

    public function update_alarm(Request $request){
        try{
            $allowedImages = ['image/jpeg','image/gif','image/png'];
            $allowedVideos = ['video/mp4','video/mpeg'];
            if($request->alarm_id != ""){
                if($request->file('file') != ""){
                    if($request->file('ringtone') != ""){
                            if($request->time != ""){
                               
                                $contentType = $request->file('file')->getClientMimeType();
                                if(in_array($contentType, $allowedImages) ){
                                    // ----------------------------image/video upload on AWS -------------------------------------------------
                                    $file= $request->file('file');
                                    $filename=$file->getClientOriginalName();
                                    $filename= time().'.'.$filename;
                                    $path =  Storage::disk('s3')->put('alarm/alarm_files', $request->file('file'));
                                    $url='https://alex-app.s3.amazonaws.com/'.$path;
    
                                    // ----------------------------ringtone upload on AWS -------------------------------------------------
                                    $ringtone= $request->file('ringtone');
                                    $tune=$ringtone->getClientOriginalName();
                                    $tune= time().'.'.$tune;
                                    $tune_path =  Storage::disk('s3')->put('alarm/alarm_ringtones', $request->file('ringtone'));
                                    $ringurl='https://alex-app.s3.amazonaws.com/'.$tune_path;
                                    Alarm::where('user_id',$request->user_id)->update([
                                    "alarm_time"=>$request->time,
                                    "user"=>$request->name,
                                    "user_id"=>$request->user_id,
                                    "file"=>$url,
                                    "ringtone"=>$ringurl
                                ]); 
                                return response()->json([
                                    "status"=>200,
                                    "message"=>"Alarm has been updated"
                                ]);
                                }elseif(in_array($contentType, $allowedVideos) ){
                                $videofile= $request->file('file');
                                $videoname=$videofile->getClientOriginalName();
                                $videoname= time().'.'.$videoname;
                                $videopath =  Storage::disk('s3')->put('alarm/alarm_files', $request->file('file'));
                                $videourl='https://alex-app.s3.amazonaws.com/'.$videopath;

                                Alarm::where('user_id',$request->user_id)->update([
                                    "alarm_time"=>$request->time,
                                    "user"=>$request->name,
                                    "user_id"=>$request->user_id,
                                    "file"=>$videourl,
                                ]); 

                                    return response()->json([
                                        "status"=>200,
                                        "message"=>'Alarm has been updated'
                                    ]);
                                }else{
                                return response()->json([
                                    "status"=>403,
                                    "message"=>'this file format is not supported'
                                ]);
                                }
                                }else{
                                    return response()->json([
                                        "status"=>403,
                                        "message"=>'time should be provided'
                                    ]);
                                }
                    
                            }else{
                            return response()->json([
                            "status"=>403,
                            "message"=>"ringtone can't be empty"
                            ]);
                            }
                        }else{
                            return response()->json([
                                "status"=>403,
                                "message"=>" file can't be empty"
                            ]);
                            }
                    }else{
                        return response()->json([
                            "status"=>403,
                            "message"=>'alarm id should be provided'
                        ]);
                    }
        } catch(\Exception $e){
            return response()->json([
                'status'=>400,
                'message'=> $e->getMessage()
            ]);
        }
    }
    public function delete_alarm(){
        try{
            
           
            if($request->alarm_id != ""){
                Alarm::where('alarm_id', $request->alarm_id)->delete();
                return response()->json([
                    "status"=>200,
                    "message"=>"Alarm has been deleted"
                ]);
            }else{
                return response()->json([
                    "status"=>403,
                    "message"=>'alarm id should be provided'
                ]);
            }
        } catch(\Exception $e){
            return response()->json([
                'status'=>400,
                'message'=> $e->getMessage()
            ]);
        }
    }
    public function update_status(Request $request){
        try{
            if($request->alarm_id != ""){
            $getAlarm=Alarm::where('alarm_id', $request->alarm_id)->first();
                if(isset($getAlarm->alarm_id)){
                    Alarm::where('alarm_id', $request->alarm_id)->update([
                        'status' => "false"
                    ]);
                    return response()->json([
                        'status'=>200,
                        'message'=> "Alarm has been updated"
                    ],200);
                }else{
                    return response()->json([
                        'status'=>403,
                        'message'=> "Requested alarm doesn't exists"
                    ],403);
                }
            }else{
                return response()->json([
                    'status'=>403,
                    'message'=> "Alarm id should be provided"
                ],403);
            }
        } catch(\Exception $e){
            return response()->json([
                'status'=>400,
                'message'=> $e->getMessage()
            ],400);
        }
    }

}
