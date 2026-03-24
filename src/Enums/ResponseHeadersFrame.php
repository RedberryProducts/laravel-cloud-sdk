<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum ResponseHeadersFrame: string
{
    case Deny = 'deny';
    case Sameorigin = 'sameorigin';
    case All = 'all';
}
