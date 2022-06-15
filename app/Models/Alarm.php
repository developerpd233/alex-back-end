<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class Alarm extends Model
{
    use HasApiTokens;
    use HasFactory;

    /**
    * @var string[]
    */
   protected $fillable = [
       'alarm_time',
       'file',
       'user',
       'user_id',
       'ringtone',
       'type'
   ];
}
