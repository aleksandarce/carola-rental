<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Admin-editable label/price/coverage text for one of the 3 fixed
 * InsuranceOption codes. Rows are seeded by the create_insurance_settings
 * migration and never created or deleted afterward — 'code' is therefore
 * not fillable, only label/daily_rate/coverage may be edited.
 *
 * @property int $id
 * @property string $code
 * @property string $label
 * @property numeric-string $daily_rate
 * @property string $coverage
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'label',
    'daily_rate',
    'coverage',
])]
class InsuranceSetting extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'daily_rate' => 'decimal:2',
        ];
    }
}
