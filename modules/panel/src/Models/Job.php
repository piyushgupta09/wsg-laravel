<?php

namespace Fpaipl\Panel\Models;

use Fpaipl\Panel\Traits\Authx;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use Authx;
    
    protected $table = 'jobs';

    protected $fillable = [
        'queue',
        'payload',
        'attempts',
        'reserved_at',
        'available_at',
        'created_at',
    ];

    public function getTableData($key)
    {
        switch ($key) {
            case 'queue': return $this->queue;
            case 'payload': return $this->payload;
            case 'attempts': return $this->attempts;
            case 'reserved_at': return $this->reserved_at;
            case 'available_at': return $this->available_at;
            default: return $this->key;
        }
        return 'Invalid Key or Class';
    }
}