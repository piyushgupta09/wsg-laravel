<?php

namespace Fpaipl\Prody\Models;

use Illuminate\Support\Str;
use Fpaipl\Panel\Traits\Authx;
use Fpaipl\Prody\Models\Product;
use Fpaipl\Prody\Models\WsgBrand;
use Spatie\MediaLibrary\HasMedia;
use Fpaipl\Panel\Traits\HasActive;
use Fpaipl\Panel\Traits\NamedSlug;
use Spatie\Activitylog\LogOptions;
use Fpaipl\Panel\Traits\SearchTags;
use Illuminate\Validation\Rules\File;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Brand extends Model implements HasMedia
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
        'tagline',
        'description',
        'website',
        'email',
        'contact_number',
        'contact_person',
    ];

    protected $searchables = [
        'name',
        'tagline',
        'description',
        'website',
        'email',
        'contact_number',
        'contact_person',
    ];
    
    const MEDIA_COLLECTION_NAME = 'brand';
    const MEDIA_CONVERSION_ICON = 's100';
    const MEDIA_CONVERSION_THUMB = 's300';
    const MEDIA_CONVERSION_IMAGE = 's500';
    const MEDIA_CONVERSION_PREVIEW = 's800';

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $count = 1;
            $modelSlug = Str::slug($model->name);
            while (self::where('slug', $modelSlug)->exists()) {
                $modelSlug = $modelSlug . '-' . $count++;
            }
            $model->slug = $modelSlug;
            $model->uuid = Str::uuid();
        });
    }

    public static function validationRules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'website' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'active' => ['nullable', 'boolean'],
            'image' => ['nullable', File::types(['jpg','jpeg','webp','png'])],
        ];
    }

    public function getTableData($key)
    {
        switch ($key) {
            case 'image': return $this->getImage(self::MEDIA_CONVERSION_ICON);
            default: return $this->{$key};
        }
    }

    /*---------------------- Relationships --------------------------*/

    public function wsgBrand(): BelongsTo
    {
        return $this->belongsTo(WsgBrand::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function productsWithTrashed(): HasMany
    {
        return $this->hasMany(Product::class)->withTrashed();
    }

    /*---------------------- Media --------------------------*/

    public function getImage($conversion = self::MEDIA_CONVERSION_THUMB): string
    {
        return $this->getFirstMediaUrl(self::MEDIA_COLLECTION_NAME, $conversion);
    }

    public function getMediaCollectionName(): string
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
            ->height(300)
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
            ->width(800)
            ->height(1000)
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
