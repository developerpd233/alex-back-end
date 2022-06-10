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

          

            $user = User::where('JWT', $request->JWT)->first();
            if(isset($user->user_id)){
            $profile = Profile::where('user_id', $user->user_id)->first();
            if($request->file('file') != ""){
                if($request->file('ringtone') != ""){
                    if($request->upp_user != ""){
                        if($request->time != ""){   
                            $contentType = $request->file('file')->getClientMimeType();
                            if(in_array($contentType, $allowedImages) ){
                                $file= $request->file('file');
                                $filename=$file->getClientOriginalName();
                                $filename= time().'.'.$filename;
                                $path =  Storage::disk('s3')->put('alarm/alarm_files', $request->file('file'));
                                $url='https://alex-app.s3.amazonaws.com/'.$path;
                                    return response()->json([
                                        "status"=>200,
                                        "message"=>'this is image',
                                        "image"=>$url
                                    ]);
                            }elseif(in_array($contentType, $allowedVideos) ){
                                $file= $request->file('file');
                                $filename=$file->getClientOriginalName();
                                $filename= time().'.'.$filename;
                                $path =  Storage::disk('s3')->put('alarm/alarm_files', $request->file('file'));
                                $url='https://alex-app.s3.amazonaws.com/'.$path;


                                    return response()->json([
                                        "status"=>200,
                                        "message"=>'this is video ',
                                        "video"=>$url
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
           
        } catch(\Exception $e){
            return response()->json([
                'status'=>400,
                'message'=> $e->getMessage()
            ]);
        }
    }
}
// curl --location --request PUT 'http://127.0.0.1:5000/auth/signup' \
// --header 'Content-Type: application/json' \
// --data-raw '{
//     "email": "testing1234@test.com",
//     "password": "1234567"
// }

