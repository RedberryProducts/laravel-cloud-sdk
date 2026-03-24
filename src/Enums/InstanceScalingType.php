<?php

namespace App\Enums\LaravelCloud;

enum InstanceScalingType: string
{
    case NONE = 'none';
    case CUSTOM = 'custom';
    case AUTO = 'auto';
}
