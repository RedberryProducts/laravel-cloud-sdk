<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum LogEntryType: string
{
    case Access = 'access';
    case Application = 'application';
    case Exception = 'exception';
    case System = 'system';
}
