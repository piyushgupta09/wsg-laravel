<?php

namespace Fpaipl\Shopy\Http\Requests;

use Fpaipl\Shopy\Models\Stock;
use Illuminate\Foundation\Http\FormRequest;

class StockEditRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $defaultValidation = Stock::validationRules();
        $specificValidation = [];
        return array_merge($specificValidation, $defaultValidation);
    }
}
