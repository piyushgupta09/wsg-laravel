<?php

namespace Fpaipl\Shopy\Http\Requests;

use Illuminate\Validation\Rule;
use Fpaipl\Shopy\Models\Delivery;
use Illuminate\Foundation\Http\FormRequest;
use Fpaipl\Shopy\Rules\DeliveryShippingAddressCheck;

class DeliveryRequest extends FormRequest
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
            'order_id' => ['required','exists:orders,id'],
            'shipping_address_id' => ['required','exists:addresses,id', new DeliveryShippingAddressCheck],
            'mode' => ['required', Rule::in(Delivery::MODE)],
            'status' => ['sometimes','required', Rule::in(Delivery::STATUS)],
        ];
    }
}
