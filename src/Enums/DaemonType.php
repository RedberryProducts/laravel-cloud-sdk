<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum DaemonType: string
{
    case Worker = 'worker';
    case Custom = 'custom';
}
