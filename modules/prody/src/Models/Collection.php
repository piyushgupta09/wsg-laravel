<?php

namespace Fpaipl\Prody\Models;

use Fpaipl\Panel\Traits\Authx;
use Fpaipl\Prody\Models\Product;
use Spatie\MediaLibrary\HasMedia;
use Fpaipl\Panel\Traits\HasActive;
use Fpaipl\Panel\Traits\NamedSlug;
use Spatie\Activitylog\LogOptions;
use Fpaipl\Panel\Traits\SearchTags;
use Illuminate\Validation\Rules\File;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Collection extends Model implements HasMedia
{
    use 
        Authx,
        InteractsWithMedia,
        LogsActivity,
        SearchTags,
        NamedSlug,
        HasActive;

    protected $fillable = [
        'name',
        'order',
        'type',
        'info',
        'shade',
        'active',
    ];

    protected $searchables = [
        'name',
        'info',
    ];

    const STATUS = ['draft', 'live'];

    const TYPES = [
        [ 'id' => 'ranged', 'name' => 'Ranged' ],
        [ 'id' => 'featured', 'name' => 'Featured' ],
        [ 'id' => 'custom', 'name' => 'Custom'],
        [ 'id' => 'recommended', 'name' => 'Recommended' ],
    ];

    const MEDIA_COLLECTION_NAME = 'collection';
    const MEDIA_CONVERSION_ICON = 's100';
    const MEDIA_CONVERSION_THUMB = 's300';
    const MEDIA_CONVERSION_IMAGE = 's500';
    const MEDIA_CONVERSION_PREVIEW = 's800';

    public static function validationRules($modelId = null)
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:collections,name,' . $modelId],
            'order' => ['nullable', 'numeric'],
            'type' => ['nullable', 'string'],
            'shade' => ['nullable', 'string'], // '#f7d5d8
            'info' => ['nullable', 'string'],
            'active' => ['nullable', 'boolean'],
            'images.*' => ['nullable', File::types(['jpg', 'webp', 'png', 'jpeg'])],
        ];
    }

    public function getTableData($key)
    {
        switch ($key) {
            case 'image': return $this->getImage(self::MEDIA_CONVERSION_ICON);
            case 'active': return $this->active ? 'Yes' : 'No';
            default: return $this->{$key};
        }
    }    

    // Scopes

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeNonRanged($query)
    {
        return $query->where('type', '!=', self::TYPES[0]['id']);
    }

    // Relationships

    public function products()
    {
        return $this->belongsToMany(Product::class, 'collection_product');
    }

    public function productOptions()
    {
        return $this->belongsToMany(Product::class, 'collection_product');
    }

    public function addProductCollection($productId)
    {
        $firstProductOptionId = Product::find($productId)?->productOptions()?->first()->id;
        if (!$firstProductOptionId) {
            return;
        }
        $this->products()->attach($productId, ['product_option_id' => $firstProductOptionId]);

    }
   
    // Media

    public function getImage($conversion = self::MEDIA_CONVERSION_THUMB): string
    {
        return $this->getFirstMediaUrl(self::MEDIA_COLLECTION_NAME, $conversion);
    }

    // check if this function is used properly
    public function getImages($conversion = self::MEDIA_CONVERSION_THUMB)
    {
        return $this->getMedia(self::MEDIA_COLLECTION_NAME)->map(function ($media) use ($conversion) {
            return $media->getUrl($conversion);
        });
    }

    public function getMediaCollectionName()
    {
        return self::MEDIA_COLLECTION_NAME;
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

        $this->addMediaConversion('s300')
            ->format('webp')
            ->width(250)
            ->height(400)
            ->sharpen(10)
            ->queued();

        $this->addMediaConversion('s500')
            ->format('webp')
            ->width(400)
            ->height(500)
            ->sharpen(10)
            ->queued();

        $this->addMediaConversion('s800')
            ->format('webp')
            ->width(600)
            ->height(800)
            ->sharpen(10)
            ->queued();
    }

    // Logging

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('model_log');
    }
}
