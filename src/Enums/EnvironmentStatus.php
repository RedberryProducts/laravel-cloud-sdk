<?php

namespace App\Enums\LaravelCloud;

enum EnvironmentStatus: string
{
    case DEPLOYING = 'deploying';
    case RUNNING = 'running';
    case HIBERNATING = 'hibernating';
    case STOPPED = 'stopped';
}
