<?php

namespace Fpaipl\Authy\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $guarded = [];
    protected $connection = 'myalt';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->connection = config('authy.altdb', config('database.default'));  
    }

    public function states()
    {
        return $this->hasMany(State::class);
    }
}
