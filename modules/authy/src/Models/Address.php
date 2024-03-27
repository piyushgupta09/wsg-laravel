<?php

namespace Fpaipl\Authy\Models;

use Fpaipl\Panel\Traits\Authx;
use Fpaipl\Shopy\Models\Delivery;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Address extends Model
{
    use
        Authx,
        LogsActivity;

    protected $fillable = [
        'title',
        'addressable_id',
        'addressable_type',
        'name',
        'lname',
        'contacts',
        'gstin',
        'line1',
        'line2',
        'state',
        'pincode',
        'country',
    ];

    public static function boot()
    {
        parent::boot();

        static::saved(function ($model) {
            $model->print = $model->printable();
            $model->saveQuietly();
        });
    }

    public function getTaxType($state = 'delhi')
    {
        $userState = strtolower($this->state);
        $userState = str_replace('new ', '', $userState);
        $userState = str_replace(' ', '', $userState);
        return $userState === $state ? 'intrastate' : 'interstate';
    }
    
    public function addressable()
    {
        return $this->morphTo();
    }

    public function deliveries()
    {
        return $this->morphMany(Delivery::class, 'deliverable');
    }

    public function displayable()
    {
        return $this->gstin . ' | ' . $this->print . '.';
    }

    public function printable()
    {
        return $this->line1 . ' ' . $this->line2 . ', ' . $this->state . ', ' . $this->pincode . '.';
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable);
    }
}
