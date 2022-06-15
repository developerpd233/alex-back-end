<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\Image_upload;
use App\Models\User;
use Storage;


class ImageController extends Controller
{
    public function image_upload(Request $request){
        try {
            $uploadedFileUrl = Cloudinary::upload($request->file('file')->getRealPath(),['folder'=> 'Images'])->getSecurePath();
            Image_upload::create([
                'image_url'=>$uploadedFileUrl 
            ]);
            return response()->json([
                'status'=>'200',
                'message' =>"Image has been inserted"
            ]);
    
        } catch(\Exception $e){
            return response()->json([
                'status'=>403,
                'message'=> $e->getMessage()
            ]);
        }
        
    }
    public function image_update(Request $request){
        try {
            $JWT= $_SERVER['HTTP_AUTHORIZATION'];

            if($JWT != ""){
            $allowedImages = ['image/jpeg','image/gif','image/png'];
           $user = User::where('JWT', $JWT)->first();
            if(isset($user->user_id)){
            // $uploadedFileUrl = Cloudinary::upload($request->file('image_url')->getRealPath(),['folder'=> 'Images'])->getSecurePath();

            $contentType = $request->file('image_url')->getClientMimeType();
            if(in_array($contentType, $allowedImages) ){
                // ----------------------------image upload on AWS -------------------------------------------------
                $file= $request->file('image_url');
                $filename=$file->getClientOriginalName();
                $filename= time().'.'.$filename;
                $path =  Storage::disk('s3')->put('user_images/', $request->file('image_url'));
                $url='https://alex-app.s3.amazonaws.com/'.$path;

                Image_upload::where('user_id',$user->user_id)->update([
                    'image_url' => $url
                ]);
          
                return response()->json([
                    'status'=>200,
                    'message' =>"Image has been updated"
                ]);
            }else{
                return response()->json([
                    "status"=>403,
                    "message"=>'this file format is not supported'
                ],403);
            }
            }else{
                return response()->json([
                    'status'=>401,
                    'message' =>"Unable to update image invalid token"
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
}