<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum EnvironmentStatus: string
{
    case DEPLOYING = 'deploying';
    case RUNNING = 'running';
    case HIBERNATING = 'hibernating';
    case STOPPED = 'stopped';
}
