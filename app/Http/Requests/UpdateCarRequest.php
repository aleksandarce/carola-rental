<?php

namespace App\Http\Requests;

use App\Enums\CarType;
use App\Enums\FuelType;
use App\Enums\Transmission;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // The route itself is already gated by ['auth', 'can:access-admin'].
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'brand' => ['required', 'string', 'max:80'],
            'type' => ['required', Rule::enum(CarType::class)],
            'daily_rate' => ['required', 'numeric', 'min:0'],
            'seat_capacity' => ['required', 'integer', 'min:1', 'max:20'],
            'doors' => ['required', 'integer', 'min:1', 'max:10'],
            'large_luggage' => ['nullable', 'integer', 'min:0', 'max:20'],
            'small_luggage' => ['nullable', 'integer', 'min:0', 'max:20'],
            'fuel_type' => ['required', Rule::enum(FuelType::class)],
            'transmission' => ['required', Rule::enum(Transmission::class)],
            'mileage' => ['nullable', 'string', 'max:30'],
            'engine_power' => ['nullable', 'string', 'max:30'],
            'location' => ['required', 'string', 'max:150'],
            'short_description' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_featured' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
