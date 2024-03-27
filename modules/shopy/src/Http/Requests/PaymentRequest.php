<?php

namespace Fpaipl\Shopy\Http\Requests;

use Illuminate\Validation\Rule;
use Fpaipl\Shopy\Models\Payment;
use Illuminate\Validation\Rules\File;
use Illuminate\Foundation\Http\FormRequest;
use Fpaipl\Shopy\Rules\PaymentOrderAmountCheck;

class PaymentRequest extends FormRequest
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
            'order' => 'bail|required|exists:orders,oid',
            'type' => ['required', Rule::in(Payment::MODE)], // previous named as mode
            'mode' => ['required', Rule::in(['25advance', '50advance', '100advance'])],
            'refid' => ['required', 'string', 'max:50'],
            'amount' => ['required', new PaymentOrderAmountCheck],
            'date' => ['required', 'date_format:Y-m-d'],
            // 'reciept' => ['required', 'mimes:jpg,jpeg,png,pdf'],
        ];
    }
}
