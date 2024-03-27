<?php

namespace Fpaipl\Panel\Models;

use Fpaipl\Panel\Traits\Authx;
use Illuminate\Database\Eloquent\Model;

class Failedjob extends Model
{
    use Authx;
    
    protected $table = 'failed_jobs';

    protected $fillable = [
        'uuid',
        'connection',
        'queue',
        'payload',
        'exception',
        'failed_at',
    ];

    public function getTableData($key)
    {
        switch ($key) {
            case 'uuid': return $this->uuid;
            case 'connection': return $this->connection;
            case 'queue': return $this->queue;
            case 'payload':
                $payload = json_decode($this->payload);
                return $payload->displayName;
            case 'failed_at': return $this->failed_at;
            default: return $this->key;
        }
        return 'Invalid Key or Class';
    }
}