<?php

namespace Fpaipl\Prody\Models;

use Fpaipl\Panel\Traits\Authx;
use Fpaipl\Prody\Models\Brand;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class WsgBrand extends Model implements HasMedia
{
    use 
        Authx,
        InteractsWithMedia,
        LogsActivity;

    protected $fillable = [
        'name',
        'info',
        'uuid',
        'server',
    ];
    
    const MEDIA_COLLECTION_NAME = 'brand';
    const MEDIA_CONVERSION_THUMB = 's100';
    const MEDIA_CONVERSION_PREVIEW = 's400';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            // $model->uuid = (string) \Illuminate\Support\Str::uuid();
            $model->uuid = 'deshigirl'; // for development only
        });
    }
   
    public static function validate(array $data): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'info' => 'nullable|string|max:255',
            'server' => 'required|url|max:255|unique:wsg_brands,server',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        return $validator->validated();
    }

    public function brands(): HasMany
    {
        return $this->hasMany(Brand::class);
    }

    /*---------------------- Media --------------------------*/

    public function getImage($conversion = self::MEDIA_CONVERSION_THUMB): string
    {
        return $this->getFirstMediaUrl(self::MEDIA_COLLECTION_NAME, $conversion);
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
        $this->addMediaConversion('s100')
            ->format('webp')
            ->width(100)
            ->height(100)
            ->sharpen(10)
            ->queued();

        $this->addMediaConversion('s400')
            ->format('webp')
            ->width(400)
            ->height(400)
            ->sharpen(10)
            ->queued();
    }

    /*---------------------- Logs --------------------------*/

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('model_log');
    }
}
