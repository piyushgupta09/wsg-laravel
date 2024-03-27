<?php

namespace Fpaipl\Prody\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Fpaipl\Panel\Traits\ManageModel;
use Fpaipl\Panel\Traits\Authx;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SizeChart extends Model
{
    use
        Authx,
        HasFactory,
        SoftDeletes,
        LogsActivity,
        ManageModel;

    // Properties

   //const INDEXABLE = false;

    /*
        Auto Generated Columns:
        id
    */
    protected $fillable = [
        'color_id', 
        'size_id',
    ];
    
    protected $cascadeDeletes = [];

    protected $CascadeSoftDeletesRestore = ['sizeWithTrashed'];
    
    protected $dependency = [];

    public function hasDependency(){
        return count($this->dependency);
    }

    public function getDependency(){
        return $this->dependency;
    }
  
    // Helper Functions

    public function getTimestamp($value) {
        return getTimestamp($this->$value);
    }

    public function getValue($key){

        return $this->$key;
    }
   
    // Relationships

    // public function size(): BelongsTo
    // {
    //     return $this->belongsTo(Size::class, 'size_id');
    // }

    public function sizeWithTrashed()
    {
        return $this->size()->withTrashed();
    }

    // Logging

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['id', 'product_id', 'size_id', 'name', 'value', 'created_at', 'updated_at', 'deleted_at'])
            ->useLogName('model_log');
    }
}