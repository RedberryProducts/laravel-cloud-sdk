<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum InstanceScalingType: string
{
    case NONE = 'none';
    case CUSTOM = 'custom';
    case AUTO = 'auto';
}
