<?php

namespace App\Http\Requests;

use App\Enums\InsuranceOption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookingInsuranceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Ownership and status-eligibility are business rules, not input
        // shape, so they live in the controller — same pattern as cancel().
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'insurance' => ['required', Rule::enum(InsuranceOption::class)],
        ];
    }
}
