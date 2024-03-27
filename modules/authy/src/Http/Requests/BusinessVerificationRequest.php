<?php

namespace Fpaipl\Authy\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BusinessVerificationRequest extends FormRequest
{
    public function authorize()
    {
        return false;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:' . implode(',', config('settings.types')),
            'lifespan' => 'required|string|in:' . implode(',', config('settings.lifespan')),
            'turnover' => 'required|string|in:' . implode(',', config('settings.turnover')),
            
        ];
    }
    
    public function messages()
    {
        return [
            'name.required' => 'Please enter your business name',
            'name.string' => 'Please enter a valid business name',
            'name.max' => 'Business name should not exceed 255 characters',
            'type.required' => 'Please select your business type',
            'type.string' => 'Please select a valid business type',
            'type.in' => 'Please select a valid business type',
            'lifespan.required' => 'Please select your business lifespan',
            'lifespan.string' => 'Please select a valid business lifespan',
            'lifespan.in' => 'Please select a valid business lifespan',
            'turnover.required' => 'Please select your business turnover',
            'turnover.string' => 'Please select a valid business turnover',
            'turnover.in' => 'Please select a valid business turnover',
        ];
    }
}
