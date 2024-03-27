<?php

namespace Fpaipl\Shopy\Models;

use App\Models\User;
use Fpaipl\Panel\Traits\Authx;
use Fpaipl\Shopy\Models\Order;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Payment extends Model implements HasMedia
{
    use Authx, LogsActivity, InteractsWithMedia;

    protected $fillable = [
        'order_id',
        'approved_by',
        'checked_by',
        'mode', // Previous named as 'type'
        'type', // e.g., upi, bank transfer
        'reference_id',
        'amount',
        'date',
        'tags', // Any additional tags for the payment
        'status', // Payment status
        'other', // JSON column for additional data
        'approved_at',
        'checked_at',
    ];

    protected $dates = ['date', 'approved_at', 'checked_at'];
    
    const STATUS =['pending', 'approved', 'rejected'];

    const MODE =['upipay','transfer'];

    const MEDIA_COLLECTION_NAME = 'reciepts';

    protected $attributes = [
        'status' => self::STATUS[0],
    ];

    public function getSid() {
        return $this->order->oid;
    }
   
    // Relationships
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function checkedBy()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }
   
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Media
    public function getMediaCollectionName()
    {
       return self::MEDIA_COLLECTION_NAME;
    }

    public function getImage($cSize = '')
    {
        $collection = collect();
        $allMedia = $this->getMedia($this->getMediaCollectionName());
        foreach ($allMedia as $media) {
            if($media->getCustomProperty('fileType') == 'application/pdf'){
                $value = $media->getUrl();
            } else {
                $value = $media->getUrl(empty($cSize) ? '' : $cSize);
            }
            $collection->push($value);
        }
        return $collection->first();
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection($this->getMediaCollectionName())
            ->useFallbackUrl(config('app.url') . '/storage/assets/images/placeholder.jpg')
            ->useFallbackPath(public_path('storage/assets/images/placeholder.jpg'))
            ->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('s200')
            ->format(Manipulations::FORMAT_WEBP)
            ->width(400)
            ->height(400)
            ->sharpen(10)
            ->queued();
        
        $this->addMediaConversion('s1200')
            ->format(Manipulations::FORMAT_WEBP)
            ->width(400)
            ->height(400)
            ->sharpen(10)
            ->queued();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnlyDirty();
    }

    public function getTableData($key)
    {
        switch ($key) {
            case 'order_id':
                return $this->order->oid;
            case 'amount':
                return '&#x20B9;' . number_format($this->amount, 0);
            case 'date':
                return 'User';
            default:
                return '';
        }
    }
}
