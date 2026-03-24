<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum ResponseHeadersRobotsTag: string
{
    case IndexFollow = 'index, follow';
    case NoindexNofollow = 'noindex, nofollow';
}
