<?php

namespace App\Enums\LaravelCloud;

enum DomainCloudflareStrategy: string
{
    case NONE = 'none';
    case DNS = 'dns';
    case DNS_PROXY = 'dns_proxy';
}
