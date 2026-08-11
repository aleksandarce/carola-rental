<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInsuranceSettingRequest extends FormRequest
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
     * No 'code' field — the 3 rows are seeded once by migration and never
     * created/renamed-by-code afterward; only label/price/coverage change.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'label' => ['required', 'string', 'max:255'],
            'daily_rate' => ['required', 'numeric', 'min:0'],
            'coverage' => ['required', 'string', 'max:500'],
        ];
    }
}
