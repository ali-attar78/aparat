<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class VideoRepublish extends Pivot
{
    protected $table = 'video_republishes';

    protected $fillable = ['user_id','video_id'];
}
