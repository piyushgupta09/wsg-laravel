<?php

namespace Fpaipl\Prody\Http\Resources;

use Fpaipl\Prody\Models\ProductRange;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductRangeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'abbr' => ProductRange::SHORT[$this->slug]?? 'small',
            'mrp' => $this->mrp,
            'rate' => $this->rate,
            'active' => $this->active,
        ];
    }
}
