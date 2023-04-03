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
    const ADMIN_TYPE = 'admin';
    const USER_TYPE = 'user';
    const TYPES = [self::ADMIN_TYPE, self::USER_TYPE];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'name',
        'email',
        'password',
        'mobile',
        'avatar',
        'website',
        'verified_code',
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
        'verified_at' => 'datetime',
    ];

    public function findForPassport($username)
    {
        $user = static::where('mobile',$username)->orwhere('email',$username)->first();
        return $user;
    }

    public function setMobileAttribute($value)
    {
        $this->attributes['mobile'] = toValidMobileNumber($value);
    }

    //region relations
    public function channel()
    {
        return $this->hasOne(Channel::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function playlist()
    {
        return $this->hasMany(PlayList::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function republishedVideos()
    {
        return $this->hasManyThrough(Video::class,
                                    RepublishVideo::class,
                                    'user_id', //video_republishes.user_id
                                    'id', // video.id
                                    'id',  // user.id
                                    'video_id'); //video_republishes.video_id
    }
    //endregion relations

    public function isAdmin(): bool
    {
        return $this->type === self::ADMIN_TYPE;
    }

    public function isBaseUser(): bool
    {
        return $this->type === self::USER_TYPE;
    }
}
