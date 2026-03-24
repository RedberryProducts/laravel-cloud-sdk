<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum CloudRegion: string
{
    case US_EAST_1 = 'us-east-1';
    case US_EAST_2 = 'us-east-2';
    case EU_CENTRAL_1 = 'eu-central-1';
    case EU_WEST_1 = 'eu-west-1';
    case EU_WEST_2 = 'eu-west-2';
    case AP_SOUTHEAST_1 = 'ap-southeast-1';
    case AP_SOUTHEAST_2 = 'ap-southeast-2';
    case CA_CENTRAL_1 = 'ca-central-1';
    case ME_CENTRAL_1 = 'me-central-1';
}
