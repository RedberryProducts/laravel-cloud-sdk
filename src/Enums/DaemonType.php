<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum DaemonType: string
{
    case WORKER = 'worker';
    case CUSTOM = 'custom';
}
