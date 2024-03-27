<?php

namespace Fpaipl\Shopy\Rules;

use Closure;

use Fpaipl\Shopy\Models\Delivery;
use Fpaipl\Shopy\Models\PickupAddress;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class OrderPickingAddressValidationRule implements DataAwareRule, ValidationRule
{
    protected $data = [];

    public function setData(array $data): static
    {
        $this->data = $data;
 
        return $this;
    }


    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->data['type'] == Delivery::TYPE[0]) {
            if (!isset($this->data['picking'])) {
                $fail('Picking address must be provided.');
            } else {
                $pickupAddress = PickupAddress::find($this->data['picking']);
                if (!$pickupAddress) {
                    $fail('Picking address must be valid address.');
                }
            }
        }
    }
}