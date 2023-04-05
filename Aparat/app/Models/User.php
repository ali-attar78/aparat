<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const TYPE_ADMIN ='admin';
    const TYPE_USER ='user ';
    const TYPES =[self::TYPE_ADMIN,self::TYPE_USER];

    protected $table='users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'type',
        'mobile',
        'name',
        'password',
        'avatar',
        'website',
        'verify_code',
        'verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'verified_code',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'verify_at',
    ];

    public function findForPassport($username)
    {
        $user= static::where('mobile',$username)->orWhere('email',$username)->first();
        return $user;
    }

    public function setMobileAttribute($value)
    {

        $this->attributes['mobile']=to_valid_mobile_number($value);

    }

    public function channel(){
      return $this->hasOne(Channel::class);
    }

    public function categories(){
        return $this->hasMany(Category::class);
    }






}
