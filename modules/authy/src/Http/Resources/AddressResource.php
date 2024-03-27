<?php

namespace Fpaipl\Authy\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $dsc = $this->pincode;

        if ($this->country) {
            $dsc = $this->country . ', ' . $dsc;
        }

        if ($this->state) {
            $dsc = $this->state . ', ' . $dsc;
        }

        if ($this->district) {
            $dsc = $this->district . ', ' . $dsc;
        }

        $normalizedUserAddress = strtolower(str_replace(' ', '', $this->addressable->account->address ?? ''));
        $normalizedAddressLine1 = strtolower(str_replace(' ', '', $this->line1 ?? ''));
        
        $default = $normalizedUserAddress === $normalizedAddressLine1;

        return [
            'id' => $this->id,
            'print' => $this->print,
            'name' => $this->name . ' ' . $this->lname,
            'contacts' => $this->contacts,
            'line1' => $this->line1,
            'line2' => $this->line2,
            'pincode' => $dsc,
            'gstin' => $this->gstin,
            'pan' => $this->pan,
            'default' => $default,
        ];
    }
}
