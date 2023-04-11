<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserFollowing extends Pivot
{
    protected $table = 'followers';
    protected $fillable = ['user_id1','user_id2','video_id'];
}
