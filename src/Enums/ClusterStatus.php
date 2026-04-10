<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum ClusterStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Maintenance = 'maintenance';
    case Inactive = 'inactive';
}
