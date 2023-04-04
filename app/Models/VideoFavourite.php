<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class VideoFavourite extends Pivot
{
    use HasFactory;
    //region model config
    protected $table   = 'video_favourites';
    protected $guarded = [];
    //endregion model config
}
