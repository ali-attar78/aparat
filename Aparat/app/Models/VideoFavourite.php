<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class VideoFavourite extends Pivot
{
    protected $table = 'video_favourites';
    protected $fillable=['user_id','video_id','user_ip'];
}
