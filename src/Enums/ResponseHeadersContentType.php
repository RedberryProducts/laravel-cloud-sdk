<?php

namespace App\Enums\LaravelCloud;

enum ResponseHeadersContentType: string
{
    case Nosniff = 'nosniff';
    case None = 'none';
}
