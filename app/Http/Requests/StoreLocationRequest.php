<?php

namespace App\Http\Requests;

use App\Enums\LocationScope;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLocationRequest extends FormRequest
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
     * There's no 'code' field here — the controller generates it from
     * 'label', the same way car slugs are generated from car names.
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
