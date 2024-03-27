<?php

namespace Fpaipl\Shopy\Models;

use Fpaipl\Panel\Traits\Authx;
use Fpaipl\Shopy\Models\Order;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\MediaCollection;

class Delivery extends Model implements HasMedia
{
    use Authx, InteractsWithMedia, LogsActivity;

    const MEDIA_COLLECTION_NAME = 'delivery';

    protected $fillable = [
        'order_id',
        'type', // dropoff, pickup
        'shipping_address',
        'name',
        'contact',
        'secret',
        'datetime',
        'note',
        'status', // 'pending', 'processing', 'completed', 'cancelled'
        'tags',
        'other', // JSON column for additional data
        'shipped_at',
        'expected_on',
        'delivered_at',
        'rejected_at'
    ];

    const STATUS =['pending', 'shipped', 'delivered', 'rejected'];

    const MODE =['standard'];
    
    const TYPE =['pickup', 'dropoff'];

    protected $attributes = [
        'status' => self::STATUS[0],
    ];

    public function getSid() {
        return $this->order->oid;
    }
        
    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function addMediaCollection(string $name): MediaCollection
    {
        return $this->addMediaCollection(self::MEDIA_COLLECTION_NAME)->singleFile();
    }

    public function getMediaFile()
    {
        if ($this->getMedia(self::MEDIA_COLLECTION_NAME)) {
            return $this->getFirstMediaUrl(self::MEDIA_COLLECTION_NAME);
        } else {
            return null;
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnlyDirty();
    }
}
