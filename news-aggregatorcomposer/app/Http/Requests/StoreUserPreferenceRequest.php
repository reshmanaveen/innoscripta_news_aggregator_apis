<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserPreferenceRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'preferred_source' => 'nullable|string|max:255',
            'preferred_category' => 'nullable|string|max:255',
            'preferred_author' => 'nullable|string|max:255',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (is_null($this->preferred_source) && is_null($this->preferred_category) && is_null($this->preferred_author)) {
                $validator->errors()->add('preferences', 'At least one preference must be specified.');
            }
        });
    }
}
