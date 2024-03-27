<?php

namespace Fpaipl\Authy\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $registerableRoles = collect(config('panel.registerable-roles'))->pluck('id')->implode(',');
        Log::info('Registerable Roles: ' . $registerableRoles);
        return [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,email',
            'password' => 'required|string|min:4|confirmed',
            'device' => 'required',
            'terms' => 'required|accepted',
            'type' => 'required|in:' . $registerableRoles
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'username.required' => 'Email is required',
            'password.required' => 'Password is required',
            'device.required' => 'Invalid Device Name',
            'terms.required' => 'You need to accept the Terms and Condition',
            'type.required' => 'User type is required',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'username' => 'Email',
            'password' => 'Password',
            'device' => 'Device Name',
            'terms' => 'Terms And Conditions',
            'type' => 'User Type',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $response = [
            'status' => 'error',
            'message' => 'Validation error',
            'errors' => $validator->errors(),
        ];

        throw new ValidationException($validator, response()->json($response, 422));
    }

    protected function prepareForValidation()
    {
        // Get 'type' from the request, default to 'user' if not present.
        $type = $this->input('type', 'user'); 
    
        $this->merge([
            'username' => strtolower($this->input('username')),
            'type' => $type,
        ]);
    }
    
}
