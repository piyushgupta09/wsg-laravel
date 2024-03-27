<?php

namespace Fpaipl\Shopy\Http\Requests;

use Illuminate\Validation\Rule;
use Fpaipl\Shopy\Models\Payment;
use Illuminate\Foundation\Http\FormRequest;

class PaymentUpdateRequest extends FormRequest
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
            'status' => ['required', Rule::in(Payment::STATUS)],
        ];
    }
}
