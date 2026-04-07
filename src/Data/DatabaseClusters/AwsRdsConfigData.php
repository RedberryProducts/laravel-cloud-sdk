<?php

namespace Redberry\LaravelCloudSdk\Data\DatabaseClusters;

use Redberry\LaravelCloudSdk\Enums\DatabaseClusterSize;
use Redberry\LaravelCloudSdk\Enums\DeploymentOption;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapOutputName(SnakeCaseMapper::class)]
class AwsRdsConfigData extends Data
{
    public function __construct(
        public string|DatabaseClusterSize $size,
        public int $storage,
        public bool $isPublic,
        public bool $usesPitr,
        public int $retentionDays,
        public string|DeploymentOption $deploymentOption,
        public ?string $maintenanceWindow,
        public ?int $readReplicas,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            size: DatabaseClusterSize::tryFrom($attributes['size']) ?? $attributes['size'],
            storage: $attributes['storage'],
            isPublic: $attributes['is_public'],
            usesPitr: $attributes['uses_pitr'],
            retentionDays: $attributes['retention_days'],
            deploymentOption: DeploymentOption::tryFrom($attributes['deployment_option']) ?? $attributes['deployment_option'],
            maintenanceWindow: $attributes['maintenance_window'] ?? null,
            readReplicas: $attributes['read_replicas'] ?? null,
        );
    }
}
