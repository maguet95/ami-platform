<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManualTradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Required (Tab 1)
            'trade_pair_id' => ['required', 'exists:trade_pairs,id'],
            'direction' => ['required', 'in:long,short'],
            'trade_date' => ['required', 'date', 'before_or_equal:today'],
            'entry_price' => ['required', 'numeric', 'gt:0'],
            'status' => ['required', 'in:open,closed'],

            // Optional execution
            'timeframe' => ['nullable', 'in:1m,5m,15m,30m,1h,4h,1d,1w'],
            'session' => ['nullable', 'in:asian,london,new_york,overlap'],
            'exit_price' => ['nullable', 'numeric', 'gt:0'],
            'stop_loss' => ['nullable', 'numeric', 'gt:0'],
            'take_profit' => ['nullable', 'numeric', 'gt:0'],
            'position_size' => ['nullable', 'numeric', 'gt:0'],
            'risk_reward_planned' => ['nullable', 'numeric', 'min:0'],
            'risk_reward_actual' => ['nullable', 'numeric'],
            'pnl' => ['nullable', 'numeric'],
            'pnl_percentage' => ['nullable', 'numeric'],
            'commission' => ['nullable', 'numeric', 'min:0'],

            // Plan & Discipline
            'had_plan' => ['nullable', 'boolean'],
            'plan_followed' => ['nullable', 'integer', 'min:1', 'max:5'],
            'entry_reason' => ['nullable', 'string', 'max:2000'],
            'invalidation_criteria' => ['nullable', 'string', 'max:2000'],
            'mistakes' => ['nullable', 'array'],
            'mistakes.*' => ['string'],
            'lessons_learned' => ['nullable', 'string', 'max:2000'],

            // Psychology
            'emotion_before' => ['nullable', 'in:calm,confident,anxious,fearful,greedy,frustrated,euphoric,neutral'],
            'emotion_during' => ['nullable', 'in:calm,confident,anxious,fearful,greedy,frustrated,euphoric,neutral'],
            'emotion_after' => ['nullable', 'in:calm,confident,anxious,fearful,greedy,frustrated,euphoric,neutral'],
            'confidence_level' => ['nullable', 'integer', 'min:1', 'max:5'],
            'stress_level' => ['nullable', 'integer', 'min:1', 'max:5'],
            'psychology_notes' => ['nullable', 'string', 'max:2000'],

            // Market context
            'market_condition' => ['nullable', 'in:trending_up,trending_down,ranging,volatile,low_volume'],
            'key_levels' => ['nullable', 'string', 'max:2000'],
            'relevant_news' => ['nullable', 'string', 'max:2000'],
            'additional_confluence' => ['nullable', 'string', 'max:2000'],

            // Reflection
            'what_i_did_well' => ['nullable', 'string', 'max:2000'],
            'what_to_improve' => ['nullable', 'string', 'max:2000'],
            'would_take_again' => ['nullable', 'boolean'],
            'overall_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'notes' => ['nullable', 'string', 'max:5000'],

            // Images
            'images' => ['nullable', 'array', 'max:' . config('journal.manual_max_images', 5)],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:' . config('journal.manual_max_image_size', 2048)],
            'captions' => ['nullable', 'array'],
            'captions.*' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'trade_pair_id.required' => 'Selecciona un par de trading.',
            'direction.required' => 'Selecciona la direccion del trade.',
            'trade_date.required' => 'La fecha es obligatoria.',
            'trade_date.before_or_equal' => 'La fecha no puede ser futura.',
            'entry_price.required' => 'El precio de entrada es obligatorio.',
            'entry_price.gt' => 'El precio debe ser mayor a 0.',
            'status.required' => 'Selecciona el estado del trade.',
            'images.max' => 'Maximo ' . config('journal.manual_max_images', 5) . ' imagenes por trade.',
            'images.*.max' => 'Cada imagen debe ser menor a ' . config('journal.manual_max_image_size', 2048) . 'KB.',
            'images.*.mimes' => 'Solo se permiten imagenes JPG, PNG o WebP.',
        ];
    }
}
