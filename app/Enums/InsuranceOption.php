<?php

namespace App\Enums;

/**
 * The fixed set of insurance packages a booking may have. This enum is the
 * single source of truth for *which 3 codes are legal* (validation and the
 * Booking model's cast). The admin-editable label/price/coverage text for
 * each code lives in the insurance_settings table (see InsuranceSetting) —
 * admins may retune those, but never add or remove a case here.
 */
enum InsuranceOption: string
{
    case Standard = 'standard';
    case Performance = 'performance';
    case Super = 'super';
}
