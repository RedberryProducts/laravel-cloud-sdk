<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum ResponseHeadersFrame: string
{
    case Deny = 'deny';
    case SameOrigin = 'sameorigin';
    case All = 'all';
}
