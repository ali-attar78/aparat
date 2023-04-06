<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    use HasFactory;
    protected $table = 'playlist';
    protected $fillable = ['user_id','title'];

    public function videos(){
        return $this->belongsToMany(Video::class,'playlist_videos');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
