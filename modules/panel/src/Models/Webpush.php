<?php

namespace Fpaipl\Panel\Models;

use Fpaipl\Panel\Traits\Authx;
use Illuminate\Database\Eloquent\Model;

class Webpush extends Model
{
    use Authx;
    
    protected $table = 'push_notifications';

    protected $fillable = [
        'subscribable_id',
        'subscribable_type',
        'endpoint',
        'public_key',
        'auth_token',
        'content_encoding',
    ];

    public function subscribable()
    {
        return $this->morphTo();
    }

    // scope
    public static function findByEndpoint($endpoint)
    {
        return static::where('endpoint', $endpoint)->first();
    }

    public function getTableData($key)
    {
        switch ($key) {
            default: return $this->key;
        }
        return 'Invalid Key or Class';
    }
}