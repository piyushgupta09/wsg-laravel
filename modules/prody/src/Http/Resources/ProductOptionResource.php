<?php

namespace Fpaipl\Prody\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductOptionResource extends JsonResource
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
            'slug' => $this->slug,
            'code' => $this->code,
            'image' => [
                's100' => $this->getImage('s100'),
                's400' => $this->getImage('s400'),
                's800' => $this->getImage('s800'),
                's1200' => $this->getImage('s1200'),
            ],
            'images' => [
                's100' => $this->getImages('s100'),
                's400' => $this->getImages('s400'),
                's800' => $this->getImages('s800'),
                's1200' => $this->getImages('s1200'),
            ],
        ];
    }
}
