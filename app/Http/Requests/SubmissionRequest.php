<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmissionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'min:2', 'max:255'],
            'last_name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'email', 'max:254'],
            'company_name' => ['nullable', 'string', 'min:2', 'max:255'],
            'website' => ['nullable', 'max:0'],
        ];
    }

    protected function prepareForValidation()
    {
        // If honeypot field has any value (including non-strings), fail validation immediately
        if ($this->has('website') && !empty($this->input('website'))) {
            abort(422, 'Validation failed');
        }
    }
}
