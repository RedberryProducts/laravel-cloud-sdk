<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum DomainCloudflareStrategy: string
{
    case None = 'none';
    case Dns = 'dns';
    case DnsProxy = 'dns_proxy';
}
