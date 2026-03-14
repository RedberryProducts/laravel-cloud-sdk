<?php

namespace App\Enums\LaravelCloud;

enum DatabaseStatus: string
{
    case CREATING = 'creating';
    case UPDATING = 'updating';
    case RESTARTING = 'restarting';
    case UPGRADING = 'upgrading';
    case AVAILABLE = 'available';
    case RESTORING = 'restoring';
    case RESTORE_FAILED = 'restore_failed';
    case DISABLED = 'disabled';
    case SNAPSHOTTING_BEFORE_ARCHIVING = 'snapshotting_before_archiving';
    case ARCHIVING = 'archiving';
    case ARCHIVED = 'archived';
    case DELETING = 'deleting';
    case DELETED = 'deleted';
    case UNKNOWN = 'unknown';
}
