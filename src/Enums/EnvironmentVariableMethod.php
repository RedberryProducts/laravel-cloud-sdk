<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum EnvironmentVariableMethod: string
{
    case Append = 'append';
    case Set = 'set';
}
