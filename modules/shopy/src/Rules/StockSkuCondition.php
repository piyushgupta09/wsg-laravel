<?php

namespace Fpaipl\Shopy\Rules;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\ValidationRule;

class StockSkuCondition implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $skuExists = DB::table('stocks')->where('sku', $value)->exists();

        if (!$skuExists) {
            if (request()->has('product_id') && request()->has('product_option_id') && request()->has('product_range_id')) {
                //  
            } else {
                $fail('The validation for SKU failed.');}
        }
    }
}
