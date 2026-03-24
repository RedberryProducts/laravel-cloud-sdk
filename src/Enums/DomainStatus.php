<?php

namespace App\Enums\LaravelCloud;

enum DomainStatus: string
{
    case PENDING = 'pending';
    case VERIFIED = 'verified';
    case FAILED = 'failed';
    case DISABLED = 'disabled';
}
