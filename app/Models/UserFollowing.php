<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserFollowing extends Pivot
{
    protected $table   = 'followers';
    protected $guarded = [];
}
