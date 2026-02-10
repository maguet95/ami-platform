<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PublicProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => [
                'nullable',
                'string',
                'min:3',
                'max:30',
                'alpha_dash',
                Rule::unique('users', 'username')->ignore($this->user()->id),
            ],
            'bio' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'location' => ['nullable', 'string', 'max:100'],
            'twitter_handle' => ['nullable', 'string', 'max:50'],
            'trading_since' => ['nullable', 'date', 'before_or_equal:today'],
            'is_profile_public' => ['boolean'],
        ];
    }
}
