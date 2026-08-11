<?php

namespace App\Enums;

enum FuelType: string
{
    case Petrol = 'Petrol';
    case Diesel = 'Diesel';
    case Electric = 'Electric';
    case Hybrid = 'Hybrid';
}
