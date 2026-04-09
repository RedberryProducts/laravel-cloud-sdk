<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum DatabaseSnapshotStatus: string
{
    case Pending = 'pending';
    case Creating = 'creating';
    case Available = 'available';
    case Failed = 'failed';
    case Deleting = 'deleting';
}
