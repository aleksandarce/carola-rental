<?php

namespace App\Http\Requests;

use App\Enums\LocationScope;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLocationRequest extends FormRequest
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
     * 'code' is intentionally not editable here — existing bookings
     * reference a location by its code, and silently changing it would
     * disconnect their stored pickup/return location (same reasoning as
     * why a car's slug is never regenerated on update).
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'label' => ['required', 'string', 'max:255'],
            'applies_to' => ['required', Rule::enum(LocationScope::class)],
            'is_active' => ['boolean'],
        ];
    }
}
