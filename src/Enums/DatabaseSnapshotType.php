<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum DatabaseSnapshotType: string
{
    case MANUAL = 'manual';
    case SCHEDULED = 'scheduled';
}
