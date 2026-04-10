<?php

namespace Redberry\LaravelCloudSdk\Data\DedicatedClusters;

use Carbon\CarbonImmutable;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\ClusterStatus;
use Redberry\LaravelCloudSdk\Enums\ClusterType;
use Redberry\LaravelCloudSdk\Enums\TenancyType;
use Spatie\LaravelData\Data;

class DedicatedClusterData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string|CloudRegion $region,
        public string|ClusterType $type,
        public string|TenancyType $tenancyType,
        public string|ClusterStatus $status,
        public ?CarbonImmutable $createdAt,
    ) {}

    public static function fromResponse(array $attributes, string $id): self
    {
        return new self(
            id: $id,
            name: $attributes['name'],
            region: CloudRegion::tryFrom($attributes['region']) ?? $attributes['region'],
            type: ClusterType::tryFrom($attributes['type']) ?? $attributes['type'],
            tenancyType: TenancyType::tryFrom($attributes['tenancy_type']) ?? $attributes['tenancy_type'],
            status: ClusterStatus::tryFrom($attributes['status']) ?? $attributes['status'],
            createdAt: isset($attributes['created_at'])
                ? CarbonImmutable::parse($attributes['created_at'])
                : null,
        );
    }
}
