<?php

namespace Fpaipl\Authy\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $guarded = [];
    protected $connection = 'myalt';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->connection = config('authy.altdb', config('database.default'));  
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function pincodes()
    {
        return $this->hasMany(Pincode::class);
    }
}
