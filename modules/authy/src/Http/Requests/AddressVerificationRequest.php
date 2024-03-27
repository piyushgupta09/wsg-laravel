<?php

namespace Fpaipl\Authy\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressVerificationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'contact' => 'required|numeric|digits:10',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'pincode' => 'required|numeric|digits:6',
            'state' => 'required|string|in:' . implode(',', config('settings.states')),
        ];
    }
    
    public function messages()
    {
        return [
            'contact.required' => 'Please enter your contact number',
            'contact.numeric' => 'Please enter a valid contact number',
            'contact.digits' => 'Please enter a valid contact number',
            'address.required' => 'Please enter your address',
            'address.string' => 'Please enter a valid address',
            'address.max' => 'Address should not exceed 255 characters',
            'city.required' => 'Please enter your city',
            'city.string' => 'Please enter a valid city',
            'city.max' => 'City should not exceed 255 characters',
            'pincode.required' => 'Please enter your pincode',
            'pincode.numeric' => 'Please enter a valid pincode',
            'pincode.digits' => 'Please enter a valid pincode',
            'state.required' => 'Please select your state',
            'state.string' => 'Please select a valid state',
            'state.in' => 'Please select a valid state',
        ];
    }
}
