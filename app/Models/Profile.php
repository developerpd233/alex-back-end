<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class Profile extends Model
{
    use HasApiTokens;
    use HasFactory;

    /**
    * @var string[]
    */
   protected $fillable = [
       'phone',
       'user_id'
   ];
}
