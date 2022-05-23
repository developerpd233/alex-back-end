<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\Image_upload;
use App\Models\User;


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
            return $e->getMessage();
        }
        
    }
    public function image_update(Request $request){
        try {
            $user = User::where('JWT', $request->JWT)->first();
            if(isset($user->user_id)){
            $uploadedFileUrl = Cloudinary::upload($request->file('image_url')->getRealPath(),['folder'=> 'Images'])->getSecurePath();
        
            Image_upload::where('user_id',$user->user_id)->update([
                'image_url' => $uploadedFileUrl
            ]);
            return response()->json([
                'status'=>'200',
                'message' =>"Image has been updated"
            ]);
        }else{
            return response()->json([
                'status'=>'200',
                'message' =>"Unable to update image invalid token"
            ]);
        }
        } catch(\Exception $e){
            return $e->getMessage();
        }
        
    }
}