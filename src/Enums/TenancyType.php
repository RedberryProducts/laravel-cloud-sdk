<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum TenancyType: string
{
    case Shared = 'shared';
    case Dedicated = 'dedicated';
}
