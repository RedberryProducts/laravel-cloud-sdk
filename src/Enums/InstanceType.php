<?php

namespace App\Enums\LaravelCloud;

enum InstanceType: string
{
    case APP = 'app';
    case SERVICE = 'service';
    case QUEUE = 'queue';
}
