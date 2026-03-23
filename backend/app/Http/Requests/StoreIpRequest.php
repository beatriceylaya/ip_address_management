<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreIpRequest extends FormRequest
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
            'ip_address' => [
                'required',
                'ip',
                Rule::unique('ip_addresses', 'ip_address')
                    ->where(function ($query) {
                        $query->where('user_id', auth()->id());
                    }),
            ],
            'label' => 'required|string|max:255',
            'comment' => 'nullable|string|max:255',
        ];
    }
}
