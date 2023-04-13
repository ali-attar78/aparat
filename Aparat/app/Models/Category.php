<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory,SoftDeletes;

    protected $table= 'categories';
    protected $fillable = ['title','icon','banner','user_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
