<?php

namespace Fpaipl\Shopy\Http\Requests;

use Illuminate\Validation\Rule;
use Fpaipl\Shopy\Models\Delivery;
use Illuminate\Foundation\Http\FormRequest;
use Fpaipl\Shopy\Rules\OrderPickingAddressValidationRule;
use Fpaipl\Shopy\Rules\OrderShippingAddressValidationRule;

class CheckoutBillingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'billing' => ['required','exists:addresses,id'],
            'same' => ['required','boolean'],
            'shipping' => ['required_if:same,0','exists:addresses,id'],
        ];
    }
}
