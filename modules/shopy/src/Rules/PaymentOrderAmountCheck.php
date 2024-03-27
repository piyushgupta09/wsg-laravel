<?php

namespace Fpaipl\Shopy\Rules;

use Closure;
use Fpaipl\Shopy\Models\Order;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class PaymentOrderAmountCheck implements DataAwareRule, ValidationRule
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
        $order = Order::where('oid', $this->data['order'])->first();
        
        if (!$order) {
            $fail('The order does not exist.');
            return;
        }
    
        if ($order->status == Order::STATUS[2]) {
            $fail('We cannot create a payment for a cancelled order.');
        }
    
        if (floatval($value) < 1.00) {
            $fail('The :attribute must be at least Rs.1');
        }
    
        // validate that user cannot pay more than the balance after deducting unapproved payment
        // i.e. total - unapproved - approved = balance
        if (floatval($value) > $order->payableAmount()) {
            $fail('The :attribute must not exceed the order payable, i.e. Rs.'.$order->payableAmount());
        }
    }
}
