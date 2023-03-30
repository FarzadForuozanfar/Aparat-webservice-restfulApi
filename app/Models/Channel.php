<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;
    protected $table    = "channels";
    protected $fillable = ['name', 'user_id', 'info', 'banner', 'socials'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
