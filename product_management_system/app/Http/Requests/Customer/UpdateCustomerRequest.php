<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255|nullable',
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:255',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'            ],
            'phone' => [
                'sometimes',
                'string',
                'regex:/^\+8801[3-9]\d{8}$|^008801[3-9]\d{8}$|^01[3-9]\d{8}$/',
            ],
            'address' => 'sometimes|string|max:255',
        ];
    }
}
