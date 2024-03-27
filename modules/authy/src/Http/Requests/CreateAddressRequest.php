<?php

namespace Fpaipl\Authy\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateAddressRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'contacts' => ['required','string', 'max:255'],
            'line1' => ['required', 'string', 'min:5', 'max:255'],
            'line2' => ['nullable', 'string', 'max:255'],
            'state' => 'required|exists:myalt.states,name',
            'district' => 'required|exists:myalt.districts,name',
            'country' => 'required|exists:myalt.countries,name',
            'pincode' => 'required|exists:myalt.pincodes,pincode',
            'gstin' => ['nullable', 'string', 'min:15', 'max:15', 'unique:addresses,gstin'],
            'pan' => ['nullable', 'string', 'min:10', 'max:10', 'unique:addresses,pan'],
        ];
    }
}
