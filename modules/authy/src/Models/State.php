<?php

namespace Fpaipl\Authy\Models;

use Fpaipl\Authy\Models\Country;
use Fpaipl\Authy\Models\District;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $guarded = [];
    protected $connection = 'myalt';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->connection = config('authy.altdb', config('database.default'));  
    }

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function districts(){
        return $this->hasMany(District::class);
    }
}
