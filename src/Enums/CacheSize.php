<?php

namespace App\Enums\LaravelCloud;

enum CacheSize: string
{
    case UPSTASH_250MB = '250mb';
    case UPSTASH_1GB = '1gb';
    case UPSTASH_2_5GB = '2.5gb';
    case UPSTASH_5GB = '5gb';
    case UPSTASH_12GB = '12gb';
    case UPSTASH_50GB = '50gb';
    case UPSTASH_100GB = '100gb';
    case UPSTASH_500GB = '500gb';
    case VALKEY_PRO_250MB = 'valkey-pro.250mb';
    case VALKEY_PRO_1GB = 'valkey-pro.1gb';
    case VALKEY_PRO_2_5GB = 'valkey-pro.2.5gb';
    case VALKEY_PRO_5GB = 'valkey-pro.5gb';
    case VALKEY_PRO_12GB = 'valkey-pro.12gb';
    case VALKEY_PRO_25GB = 'valkey-pro.25gb';
    case VALKEY_PRO_50GB = 'valkey-pro.50gb';
}
