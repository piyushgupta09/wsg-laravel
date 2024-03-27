<?php

namespace Fpaipl\Panel\Traits;

use App\Models\User;

trait BelongsToUser
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userWithTrashed()
    {
        return $this->user()->withTrashed();
    }
}
