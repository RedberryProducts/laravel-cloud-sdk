<?php

namespace App\Enums\LaravelCloud;

enum ResponseHeadersFrame: string
{
    case Deny = 'deny';
    case Sameorigin = 'sameorigin';
    case All = 'all';
}
