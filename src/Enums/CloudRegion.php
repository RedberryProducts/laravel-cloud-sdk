<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum CloudRegion: string
{
    case UsEast1 = 'us-east-1';
    case UsEast2 = 'us-east-2';
    case EuCentral1 = 'eu-central-1';
    case EuWest1 = 'eu-west-1';
    case EuWest2 = 'eu-west-2';
    case ApSoutheast1 = 'ap-southeast-1';
    case ApSoutheast2 = 'ap-southeast-2';
    case CaCentral1 = 'ca-central-1';
    case MeCentral1 = 'me-central-1';
}
