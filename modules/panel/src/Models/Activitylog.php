<?php

namespace Fpaipl\Panel\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Fpaipl\Panel\Traits\Authx;
use Illuminate\Database\Eloquent\Model;

class Activitylog extends Model
{
    use Authx;
    
    protected $table = 'activity_log';

    protected $fillable = [
        'log_name',
        'description',
        'subject_id',
        'subject_type',
        'causer_id',
        'causer_type',
        'properties',
        'batch_uuid',
        'created_at',
        'updated_at',
    ];

    public function getTableData($key)
    {
        switch ($key) {
            case 'name': 
                return $this->log_name;
    
            case 'description': 
                return $this->description;

            case 'event':
                return $this->event;
    
            case 'subject':
                if (class_exists($this->subject_type)) {
                    $subject = $this->subject_type::find($this->subject_id);
                    return $subject ? $subject->name . ' (' . Str::afterLast($this->subject_type, '\\') . ')' : 'N/A';
                }
                break;
    
            case 'causer':
                if (class_exists($this->causer_type)) {
                    $causer = $this->causer_type::find($this->causer_id);
                    return $causer ? $causer->name . ' (' . Str::afterLast($this->causer_type, '\\') . ')' : 'N/A';
                }
                break;
    
            case 'properties':
                return json_encode(json_decode($this->properties)->attributes, JSON_PRETTY_PRINT);

            default: 
                return $this->key;
        }
        return 'Invalid Key or Class';
    }
    
}