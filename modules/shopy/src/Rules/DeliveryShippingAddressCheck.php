<?php

namespace Fpaipl\Shopy\Rules;

use Closure;
use Fpaipl\Authy\Models\Address;
use Illuminate\Contracts\Validation\ValidationRule;

class DeliveryShippingAddressCheck implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $address = Address::findOrFail($value);

        if(!($address->sb_same == 1 || $address->type == 'shipping')){
            $fail('The :attribute must be type shipping');
        }
    }
}
