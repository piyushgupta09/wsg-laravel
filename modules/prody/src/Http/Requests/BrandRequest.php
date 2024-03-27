<?php

namespace Fpaipl\Prody\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Fpaipl\Prody\Models\Brand;

class BrandRequest extends FormRequest
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
        $rules = Brand::validationRules();

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            // Assuming 'brand' is the route parameter name for the Brand model's identifier
            // and 'id' is the primary key field of the Brand model.
            $brandId = $this->route('brand')->id; // Use 'id' or the primary key attribute of the Brand model
            $rules['name'] = ['required', 'unique:brands,name,' . $brandId];
        }

        return $rules;
    }   
}
