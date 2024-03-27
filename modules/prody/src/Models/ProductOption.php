<?php

namespace Fpaipl\Prody\Models;

use Fpaipl\Prody\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductOption extends Model
{
    const MEDIA_CONVERSION_THUMB = 's100';
    // const MEDIA_CONVERSION_CARD = 's300';
    const MEDIA_CONVERSION_PREVIEW = 's400';
    const MEDIA_CONVERSION_BANNER = 's800';
    const MEDIA_CONVERSION_FULL = 's1200';

    protected $guarded = [];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function hasImage($conversion=self::MEDIA_CONVERSION_THUMB)
    {
        return !empty($this->getImage($conversion));
    }

    public function getImage($conversion=self::MEDIA_CONVERSION_THUMB)
    {
        $image = json_decode($this->image, true);
        return isset($image[$conversion]) ? $image[$conversion] : null;
    }

    public function getImages($conversion=self::MEDIA_CONVERSION_THUMB)
    {
        $images = json_decode($this->images, true);
        return isset($images[$conversion]) ? $images[$conversion] : null;
    }

    public function scopeLatest($query)
    {
        return $query->sortByDesc('id');
    }
}
