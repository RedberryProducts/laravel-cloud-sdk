<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum DomainStatus: string
{
    case PENDING = 'pending';
    case VERIFIED = 'verified';
    case FAILED = 'failed';
    case DISABLED = 'disabled';
}
