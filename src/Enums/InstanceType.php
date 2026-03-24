<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum InstanceType: string
{
    case APP = 'app';
    case SERVICE = 'service';
    case QUEUE = 'queue';
    case SERVERLESS_QUEUE = 'serverless_queue';
}
