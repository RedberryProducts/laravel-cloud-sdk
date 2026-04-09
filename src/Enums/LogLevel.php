<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum LogLevel: string
{
    case Info = 'info';
    case Warning = 'warning';
    case Error = 'error';
    case Debug = 'debug';
}
