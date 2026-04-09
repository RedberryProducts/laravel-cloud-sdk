<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum EnvironmentStatus: string
{
    case Deploying = 'deploying';
    case Running = 'running';
    case Hibernating = 'hibernating';
    case Stopped = 'stopped';
}
