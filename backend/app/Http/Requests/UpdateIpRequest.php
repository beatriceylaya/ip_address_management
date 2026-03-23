<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateIpRequest extends FormRequest
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
        $ipAddress = $this->route('ip_address');

        return [
            'ip_address' => [
                'sometimes',
                'ip',
                Rule::unique('ip_addresses', 'ip_address')
                    ->where(function ($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->ignore($ipAddress?->id),
            ],
            'label' => 'sometimes|string|max:255',
            'comment' => 'nullable|string|max:255',
        ];
    }
}
