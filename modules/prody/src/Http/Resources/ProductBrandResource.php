<?php

namespace Fpaipl\Prody\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductBrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'style' => 'background-color: #231f20; color: #e9c3ae; font-size: 1.2rem;',
        ];
    }
}
