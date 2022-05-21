<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class Image_upload extends Model
{
    use HasApiTokens;
    use HasFactory;

    /**
    * @var string[]
    */
   protected $fillable = [
       'image_url',
       'user_id',
       'JWT'
   ];
}
