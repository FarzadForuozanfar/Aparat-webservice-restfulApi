<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class VideoView extends Pivot
{
    protected $table   = 'video_views';
    protected $guarded = [];
}
