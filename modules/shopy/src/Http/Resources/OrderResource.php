<?php

namespace Fpaipl\Shopy\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Fpaipl\Shopy\Http\Resources\PaymentResource;
use Fpaipl\Shopy\Http\Resources\DeliveryResource;
use Fpaipl\Shopy\Http\Resources\OrderProductResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'oid' => $this->oid,
            'date' => Carbon::parse($this->created_at)->format('d M'),
            'created_at' => Carbon::parse($this->created_at)->format('d-m-Y H:i A'),
            'billing_address' => $this->billing_address,
            'customer' => [
                'name' => $this->user?->account?->name,
                'gstin' => $this->user?->account?->gstin,
                'phone' => $this->user?->account?->contact,
            ],
            'status' => $this->status,
            'total' => $this->total,
            'amount' => $this->amount,
            'tax' => $this->tax,
            'payable' => $this->payableAmount(),
            'payment' => [
                'type' => $this->orderPayments->first()?->type,
                'mode' => $this->pay_mode,
                'approved' => $this->approvedPayments(),
                'unapproved' => $this->unapprovedPayments(),
                'rejected' => $this->rejectedPayments(),
                'discount' => $this->orderCouponAmount(),
                'dueAfterDiscount' => $this->payableAmountAfterDiscount(),
                'payBefore' => $this->computePayBefore(),
                'payAfter' => $this->computePayAfter(),
                'image' => $this->paymentStatusImage(),
                'status' => $this->paymentStatus(),
            ],
            'delivery' => [
                'image' => $this->deliveryStatusImage(),
                'status' => $this->deliveryStatus(),
            ],
            'order_coupon' => $this->orderCoupon,
            'expected_on' => Carbon::parse($this->orderDeliveries()->first()->datetime)->diffForHumans(),
            'deliveries' => DeliveryResource::collection($this->orderDeliveries),
            'payments' => PaymentResource::collection($this->orderPayments),
            'orderProducts'=> OrderProductResource::collection($this->orderProducts),
            'skus' => $this->orderProducts->sum('skus'),
            'quantity' => $this->orderProducts->sum('quantity'),
            // 'tId' => '14843148435154',
            // 'refId' => '5644g35h8fg4h5gfh',
        ];
    }
}
