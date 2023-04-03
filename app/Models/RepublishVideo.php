<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class RepublishVideo extends Pivot
{
    use HasFactory;
    protected $table = 'video_republishes';
}
