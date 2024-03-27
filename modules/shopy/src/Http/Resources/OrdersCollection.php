<?php

namespace Fpaipl\Shopy\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrdersCollection extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'date' => Carbon::parse($this->created_at)->format('d M'),
            'oid' => $this->oid,
            'tags' => $this->tags,
            'status' => $this->status,
            'skus' => $this->orderProducts->sum('skus'),
            'quantity' => $this->orderProducts->sum('quantity'),
            'expected_on' => Carbon::parse($this->orderDeliveries()->first()->datetime)->diffForHumans(),
            'total' => $this->total,
            'payment' => [
                'image' => $this->paymentStatusImage(),
                'status' => $this->paymentStatus(),
            ],
            'delivery' => [
                'image' => $this->deliveryStatusImage(),
                'status' => $this->deliveryStatus(),
            ],
            'created_at' => Carbon::parse($this->created_at)->format('d-m-Y'),
        ];
    }
}
