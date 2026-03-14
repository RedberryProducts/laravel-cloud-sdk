<?php

namespace App\Data\LaravelCloud\DatabaseClusters;

use App\Enums\LaravelCloud\DeploymentOption;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapOutputName(SnakeCaseMapper::class)]
class AwsRdsConfigData extends Data
{
    public function __construct(
        public string $size,
        public int $storage,
        public bool $isPublic,
        public bool $usesPitr,
        public int $retentionDays,
        public DeploymentOption $deploymentOption,
        public ?string $maintenanceWindow,
        public ?int $readReplicas,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            size: $attributes['size'],
            storage: $attributes['storage'],
            isPublic: $attributes['is_public'],
            usesPitr: $attributes['uses_pitr'],
            retentionDays: $attributes['retention_days'],
            deploymentOption: DeploymentOption::from($attributes['deployment_option']),
            maintenanceWindow: $attributes['maintenance_window'] ?? null,
            readReplicas: $attributes['read_replicas'] ?? null,
        );
    }
}
