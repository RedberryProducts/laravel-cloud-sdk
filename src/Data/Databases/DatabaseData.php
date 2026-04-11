<?php

namespace Redberry\LaravelCloudSdk\Data\Databases;

use Carbon\CarbonImmutable;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseClusterData;
use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentData;
use Spatie\LaravelData\Data;

class DatabaseData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public ?CarbonImmutable $createdAt,
        public ?DatabaseClusterData $databaseCluster = null,
        /** @var EnvironmentData[] */
        public array $environments = [],
    ) {}

    public static function fromResponse(array $attributes, string $id): self
    {
        return new self(
            id: $id,
            name: $attributes['name'],
            createdAt: isset($attributes['created_at'])
                ? CarbonImmutable::parse($attributes['created_at'])
                : null,
        );
    }
}
