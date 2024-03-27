<?php

namespace Fpaipl\Authy\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class ValidateDocumentSetsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'gstin' => 'nullable|string|size:15|regex:/\d{2}[a-z]{5}\d{4}[a-z]{1}[a-z\d]{1}[z]{1}[a-z\d]{1}/i',
            'gstin_file' => 'nullable|file',
            'aadhar' => 'nullable|string|size:12|regex:/\d{12}/i',
            'aadhar_file' => 'nullable|file',
            'bank' => 'nullable|string|size:11|regex:/\d{11}/i',
            'bank_file' => 'nullable|file',
            'pan' => 'nullable|string|size:10|regex:/[a-z]{5}\d{4}[a-z]{1}/i',
            'pan_file' => 'nullable|file',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $documentSets = [
                'gstin' => ['gstin', 'gstin_file'],
                'aadhar' => ['aadhar', 'aadhar_file'],
                'bank' => ['bank', 'bank_file'],
                'pan' => ['pan', 'pan_file']
            ];
            $validSets = 0;

            foreach ($documentSets as $set) {
                $isValidSet = true;

                foreach ($set as $field) {
                    if (Str::endsWith($field, '_file')) {
                        if (!$this->hasFile($field)) {
                            $isValidSet = false;
                            break;
                        }
                    } else {
                        if ($this->input($field) === null || $this->input($field) === "null") {
                            $isValidSet = false;
                            break;
                        }
                    }
                }

                if ($isValidSet) {
                    $validSets++;
                }
            }

            if ($validSets < 2) {
                $validator->errors()->add('documents', 'At least two sets of documents are required.');
            }
        });
    }
}
