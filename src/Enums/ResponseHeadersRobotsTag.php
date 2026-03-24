<?php

namespace App\Enums\LaravelCloud;

enum ResponseHeadersRobotsTag: string
{
    case IndexFollow = 'index, follow';
    case NoindexNofollow = 'noindex, nofollow';
}
