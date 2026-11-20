<?php

namespace App\Enums;

enum LocationScope: string
{
    case Pickup = 'pickup';
    case Return = 'return';
    case Both = 'both';
}
