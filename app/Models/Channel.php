<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class  Channel extends Model
{
    use HasFactory;
    //region model configs
    protected $table    = "channels";
    protected $fillable = ['name', 'user_id', 'info', 'banner', 'socials'];
    //endregion

    //region relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function videos()
    {
        return $this->user->videos();
    }
    //endregion

    //region override methods
    public function getRouteKeyName(): string
    {
        return 'name';
    }
    //endregion
}
