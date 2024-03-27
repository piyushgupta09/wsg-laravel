<?php

namespace Fpaipl\Panel\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Fpaipl\Panel\Traits\Authx;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use Authx;
    
    protected $table = 'notifications';

    protected $fillable = [
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at',
        'created_at',
        'updated_at',
    ];

    public function getTableData($key)
    {
        switch($key) {
            case 'type':
                return Str::afterLast($this->type, '\\');
            case 'recipient':
                return $this->notifiable_type::find($this->notifiable_id)->name . ' (' . Str::afterLast($this->notifiable_type, '\\') . ')';
            case 'read_at':
                return $this->read_at ? Carbon::parse($this->read_at)->diffForHumans() : 'No';
            case 'message':
                return json_decode($this->data)->title;
            default: return $this->key;
        }
    }
}