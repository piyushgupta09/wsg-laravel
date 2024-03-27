<?php

namespace Fpaipl\Shopy\Http\Requests;

use Illuminate\Validation\Rule;
use Fpaipl\Shopy\Models\Delivery;
use Illuminate\Foundation\Http\FormRequest;
use Fpaipl\Shopy\Rules\OrderPickingAddressValidationRule;
use Fpaipl\Shopy\Rules\OrderShippingAddressValidationRule;

class CheckoutDeliveryRequest extends FormRequest
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
            'type' => [
                Rule::in(Delivery::TYPE),
                new OrderPickingAddressValidationRule,
                new OrderShippingAddressValidationRule,
            ],
            'name' => ['nullable', 'string', 'max:255'],
            'contact' => ['nullable', 'string', 'max:255'],
            'secret' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:255'],
        ];
    }
}
