<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    protected $table='videos';
    protected $fillable=[
        'user_id' ,
        'category_id',
        'channel_category_id',
        'slug',
        'title' ,
        'info',
        'duration' ,
        'banner' ,
        'publish_at',
    ];

    public function playlist()
    {
        return $this->belongsToMany(Playlist::class,'playlist_videos')->first();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class,'video_tags');
    }

}
