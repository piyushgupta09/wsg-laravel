<?php

namespace Fpaipl\Shopy\Rules;

use Closure;
use Fpaipl\Authy\Models\Address;
use Fpaipl\Shopy\Models\Delivery;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class OrderShippingAddressValidationRule implements DataAwareRule, ValidationRule
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
        if ($this->data['type'] == Delivery::TYPE[1]) {
            if (!isset($this->data['shipping'])) {
                $fail('Shipping address must be provided.');
            } else {
                $shippingAddress = Address::where('id', $this->data['shipping'])->first();
                if (!$shippingAddress) {
                    $fail('Shipping Address must be valid address.');
                }
            }
        }
    }
}
