<?php

namespace App\Enums\LaravelCloud;

enum CacheStrategy: string
{
    case Default = 'default';
    case Bypass = 'bypass';
}
