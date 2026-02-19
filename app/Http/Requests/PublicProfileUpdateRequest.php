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
            'headline' => ['nullable', 'string', 'max:100'],
            'twitter_handle' => ['nullable', 'string', 'max:50'],
            'instagram_handle' => ['nullable', 'string', 'max:50'],
            'youtube_handle' => ['nullable', 'string', 'max:50'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'trading_since' => ['nullable', 'date', 'before_or_equal:today'],
            'is_profile_public' => ['boolean'],
            'share_manual_journal' => ['boolean'],
            'share_automatic_journal' => ['boolean'],
            'automatic_journal_account_type' => ['nullable', 'string', 'in:real,demo'],
        ];
    }
}
