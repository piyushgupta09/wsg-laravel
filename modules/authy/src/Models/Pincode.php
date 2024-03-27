<?php

namespace Fpaipl\Authy\Models;

use Fpaipl\Authy\Models\District;
use Illuminate\Database\Eloquent\Model;

class Pincode extends Model
{    
    protected $guarded = [];
    protected $connection = 'myalt';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->connection = config('authy.altdb', config('database.default'));  
    }

    public function getRouteKeyName()
    {
        return 'pincode';
    }

    public function district(){
        return $this->belongsTo(District::class);
    }
}
