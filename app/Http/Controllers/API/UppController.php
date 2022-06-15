<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alarm;
use App\Models\User;
use App\Models\Profile;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Storage;

class UppController extends Controller
{
    public function UppAlarm(Request $request){
        try{
            $allowedImages = ['image/jpeg','image/gif','image/png'];
            $allowedVideos = ['video/mp4','video/mpeg'];

            $JWT= $_SERVER['HTTP_AUTHORIZATION'];
            if($JWT != ""){
           $user = User::where('JWT', $JWT)->first();
            if(isset($user->user_id)){
            $profile = Profile::where('user_id', $user->user_id)->first();
            if($request->file('file') != ""){
                if($request->file('ringtone') != ""){
                    if($request->upp_user != ""){
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

                                        Alarm::create([
                                            "alarm_time"=>$request->time,
                                            "user"=>$profile->name,
                                            "user_id"=>$request->upp_user,
                                            "type"=>"upp alarm",
                                            "file"=>$url,
                                            "ringtone"=>$ringurl
                                        ]); 

                                    return response()->json([
                                        "status"=>200,
                                        "message"=>'Upp Alarm has been set'
                                    ]);
                            }elseif(in_array($contentType, $allowedVideos) ){
                                $videofile= $request->file('file');
                                $videoname=$videofile->getClientOriginalName();
                                $videoname= time().'.'.$videoname;
                                $videopath =  Storage::disk('s3')->put('alarm/alarm_files', $request->file('file'));
                                $videourl='https://alex-app.s3.amazonaws.com/'.$videopath;

                                Alarm::create([
                                    "alarm_time"=>$request->time,
                                    "user"=>$profile->name,
                                    "user_id"=>$request->upp_user,
                                    "type"=>"upp alarm",
                                    "file"=>$videourl,
                                ]); 

                                    return response()->json([
                                        "status"=>200,
                                        "message"=>'Upp Alarm has been set'
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
                        "message"=>'upp user cannot be empty'
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
        } else{
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
}


