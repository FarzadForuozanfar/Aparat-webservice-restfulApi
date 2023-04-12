<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlayList extends Model
{
    use HasFactory, SoftDeletes;

    //region model config
    protected $table   = 'playlist';
    protected $guarded = [];
    //endregion model config

    //region relations
    public function videos()
    {
        return $this->belongsToMany(Video::class, 'playlists_videos')->orderBy('playlists_videos.id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    //endregion relations

    public function toArray()
    {
        $data          = parent::toArray();
        $data['count'] = $this->videos()->count();

        return $data;
    }
}
