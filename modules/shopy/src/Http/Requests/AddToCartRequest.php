<?php

namespace Fpaipl\Shopy\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $cart = $this->route('cart');
        return $cart->user->id === $this->user()->id;
    }
    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'order_type' => ['required', 'in:preset,custom'],
            'product_id' => ['required', 'exists:products,slug'],
            'items' => ['required', 'array'],
            'items.*.option_id' => ['required', 'exists:product_options,id'],
            'items.*.range_id' => ['required', 'exists:product_ranges,id'],
            'items.*.quantity' => ['required', 'integer', 'min:0'],
        ];
    }
}
