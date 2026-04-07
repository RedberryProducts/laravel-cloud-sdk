<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum DatabaseSnapshotStatus: string
{
    case PENDING = 'pending';
    case CREATING = 'creating';
    case AVAILABLE = 'available';
    case FAILED = 'failed';
    case DELETING = 'deleting';
}
