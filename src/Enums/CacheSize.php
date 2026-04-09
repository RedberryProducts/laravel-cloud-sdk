<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum CacheSize: string
{
    case Upstash250Mb = '250mb';
    case Upstash1Gb = '1gb';
    case Upstash2_5Gb = '2.5gb';
    case Upstash5Gb = '5gb';
    case Upstash12Gb = '12gb';
    case Upstash50Gb = '50gb';
    case Upstash100Gb = '100gb';
    case Upstash500Gb = '500gb';
    case ValkeyPro250Mb = 'valkey-pro.250mb';
    case ValkeyPro1Gb = 'valkey-pro.1gb';
    case ValkeyPro2_5Gb = 'valkey-pro.2.5gb';
    case ValkeyPro5Gb = 'valkey-pro.5gb';
    case ValkeyPro12Gb = 'valkey-pro.12gb';
    case ValkeyPro25Gb = 'valkey-pro.25gb';
    case ValkeyPro50Gb = 'valkey-pro.50gb';
}
