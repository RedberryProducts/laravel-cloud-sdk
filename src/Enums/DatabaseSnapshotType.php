<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum DatabaseSnapshotType: string
{
    case Manual = 'manual';
    case Scheduled = 'scheduled';
}
