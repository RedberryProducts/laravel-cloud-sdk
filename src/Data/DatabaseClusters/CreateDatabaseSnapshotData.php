<?php

namespace Redberry\LaravelCloudSdk\Data\DatabaseClusters;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class CreateDatabaseSnapshotData extends Data
{
    public function __construct(
        public string $name,
        public string|null|Optional $description = new Optional,
    ) {}
}
