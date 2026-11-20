<?php

namespace App\Http\Requests;

use App\Enums\BookingStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookingRequest extends FormRequest
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
     * This only checks that the submitted value is a real status — whether
     * it's a *legal transition* from the booking's current status is
     * business logic, not input shape, so that check lives in the
     * controller instead.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(BookingStatus::class)],
        ];
    }
}
