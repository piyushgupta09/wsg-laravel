<?php

namespace Fpaipl\Shopy\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Fpaipl\Shopy\Http\Resources\CartProductResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id, // used to send cart update request param to server
            'name' => $this->name,
            'products' => CartProductResource::collection($this->whenLoaded('cartProducts')),
        ];
    }
}
