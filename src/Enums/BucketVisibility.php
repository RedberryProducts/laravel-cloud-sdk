<?php

namespace App\Enums\LaravelCloud;

enum BucketVisibility: string
{
    case PRIVATE = 'private';
    case PUBLIC = 'public';
}
