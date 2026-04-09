<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum DatabaseStatus: string
{
    case Creating = 'creating';
    case Updating = 'updating';
    case Restarting = 'restarting';
    case Upgrading = 'upgrading';
    case Available = 'available';
    case Restoring = 'restoring';
    case RestoreFailed = 'restore_failed';
    case Disabled = 'disabled';
    case SnapshottingBeforeArchiving = 'snapshotting_before_archiving';
    case Archiving = 'archiving';
    case Archived = 'archived';
    case Deleting = 'deleting';
    case Deleted = 'deleted';
    case Unknown = 'unknown';
}
