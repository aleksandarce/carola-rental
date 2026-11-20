<?php

namespace App\Http\Requests;

use App\Enums\InsuranceOption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // The route itself requires an authenticated user via 'auth' middleware.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * Pickup/return locations are admin-managed data (Location model), not
     * a fixed enum, so they're validated against the locations table —
     * active, and scoped to the right side of the trip.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            // 'after_or_equal:now', not 'today' — pickup/return now carry a
            // real time, so a past hour earlier today must be rejected too.
            'start_date' => ['required', 'date', 'after_or_equal:now'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'insurance' => ['required', Rule::enum(InsuranceOption::class)],
            'notes' => ['nullable', 'string', 'max:1000'],
            'pickup_location' => [
                'required',
                'string',
                Rule::exists('locations', 'code')
                    ->where('is_active', true)
                    ->whereIn('applies_to', ['pickup', 'both']),
            ],
            'return_location' => [
                'required',
                'string',
                Rule::exists('locations', 'code')
                    ->where('is_active', true)
                    ->whereIn('applies_to', ['return', 'both']),
            ],
        ];
    }
}
