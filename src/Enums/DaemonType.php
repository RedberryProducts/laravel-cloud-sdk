<?php

namespace App\Enums\LaravelCloud;

enum DaemonType: string
{
    case WORKER = 'worker';
    case CUSTOM = 'custom';
}
