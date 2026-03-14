<?php

namespace App\Enums\LaravelCloud;

enum DomainVerificationMethod: string
{
    case PRE_VERIFICATION = 'pre_verification';
    case REAL_TIME = 'real_time';
}
