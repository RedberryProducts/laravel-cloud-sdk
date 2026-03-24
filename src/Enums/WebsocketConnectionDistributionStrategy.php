<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum WebsocketConnectionDistributionStrategy: string
{
    case EVENLY = 'evenly';
    case CUSTOM = 'custom';
}
