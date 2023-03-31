<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $fillable = ['title'];

    public function videos()
    {
        return $this->belongsToMany(Video::class, 'video_tags');
    }
}
