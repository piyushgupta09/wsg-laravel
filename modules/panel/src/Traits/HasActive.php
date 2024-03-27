<?php

namespace Fpaipl\Panel\Traits;

trait HasActive
{
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('active', false);
    }

    public function markActive()
    {
        $this->active = true;
        $this->save();
    }

    public function markInactive()
    {
        $this->active = false;
        $this->save();
    }
}
