<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\Image_upload;


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
            $uploadedFileUrl = Cloudinary::upload($request->file('image_url')->getRealPath(),['folder'=> 'Images'])->getSecurePath();
        
            Image_upload::where('JWT',$request->JWT)->update([
                'image_url' => $uploadedFileUrl
            ]);
            return response()->json([
                'status'=>'200',
                'message' =>"Image has been updated"
            ]);
    
        } catch(\Exception $e){
            return $e->getMessage();
        }
        
    }
}
