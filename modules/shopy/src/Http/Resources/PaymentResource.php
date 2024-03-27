<?php

namespace Fpaipl\Shopy\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => $this->type,
            'mode' => $this->mode,
            'reference_id' => $this->reference_id,
            'amount' => $this->amount,
            'date' => $this->date,
            'status' => $this->status,
            'approved_by' => $this->approvedBy,
            'approved_at' => $this->approved_at,
            'checked_by' => $this->checkedBy,
            'checked_at' => $this->checked_at,
            'image' => $this->getImage('s200'),
            'preview' => $this->getImage('s1200'),
        ];
    }
}
