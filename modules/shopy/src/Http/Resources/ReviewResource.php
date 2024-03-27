<?php

namespace Fpaipl\Shopy\Http\Resources;

use Fpaipl\Prody\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'stars' => 4,
            'tags' => ['test', 'test2'],
            'users'=> [
                (object) [
                    'name' => 'test',
                    'stars' => 3,
                    'product' => Product::find(1)->name,
                    'comment' => 'lore ipsum test',
                    'images' => [
                        'https://picsum.photos/200/300',
                        'https://picsum.photos/200/300',
                        'https://picsum.photos/200/300',
                        'https://picsum.photos/200/300',
                        'https://picsum.photos/200/300',
                        'https://picsum.photos/200/300',
                        'https://picsum.photos/200/300',
                        'https://picsum.photos/200/300',
                        'https://picsum.photos/200/300',
                        'https://picsum.photos/200/300',
                        'https://picsum.photos/200/300',
                    ],
                ],
                (object) [
                    'name' => 'd test',
                    'stars' => 5,
                    'product' => Product::find(1)->name,
                    'comment' => 'ddd lore ipsum test',
                    'images' => [
                        'https://picsum.photos/200/300',
                        'https://picsum.photos/200/300',
                        'https://picsum.photos/200/300',
                    ],
                ]
            ]

            // 'name' => $this->name,
            // 'stars' => $this->stars,
            // 'catalog' => $this->catalog,
            // 'comment' => $this->comment,
            // 'tags' => $this->tags,
        ];
    }
}
