<?php

namespace Redberry\LaravelCloudSdk\Data\DatabaseClusters;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class RestoreDatabaseClusterData extends Data
{
    public function __construct(
        public string $name,
        public string|null|Optional $restoreTime = new Optional,
        public string|null|Optional $databaseSnapshotId = new Optional,
    ) {}
}
