<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum WebsocketConnectionDistributionStrategy: string
{
    case Evenly = 'evenly';
    case Custom = 'custom';
}
