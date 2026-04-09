<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum LogFilterType: string
{
    case All = 'all';
    case Application = 'application';
    case Access = 'access';
}
