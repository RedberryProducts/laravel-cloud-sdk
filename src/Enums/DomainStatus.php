<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum DomainStatus: string
{
    case Pending = 'pending';
    case Verified = 'verified';
    case Failed = 'failed';
    case Disabled = 'disabled';
}
