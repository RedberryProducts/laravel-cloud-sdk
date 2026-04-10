<?php

namespace Redberry\LaravelCloudSdk\Data\Instances;

use Carbon\CarbonImmutable;
use Redberry\LaravelCloudSdk\Data\BackgroundProcesses\BackgroundProcessData;
use Redberry\LaravelCloudSdk\Enums\InstanceScalingType;
use Redberry\LaravelCloudSdk\Enums\InstanceSize;
use Redberry\LaravelCloudSdk\Enums\InstanceType;
use Spatie\LaravelData\Data;

class InstanceData extends Data
{
    /**
     * @param  BackgroundProcessData[]  $backgroundProcesses
     */
    public function __construct(
        public string $id,
        public string $name,
        public string|InstanceType $type,
        public string|InstanceSize $size,
        public string|InstanceScalingType $scalingType,
        public int $minReplicas,
        public int $maxReplicas,
        public bool $usesScheduler,
        public ?int $scalingCpuThresholdPercentage,
        public ?int $scalingMemoryThresholdPercentage,
        public array $backgroundProcesses,
        public ?CarbonImmutable $createdAt,
    ) {}

    public static function fromResponse(array $attributes, string $id, array $backgroundProcesses = []): self
    {
        return new self(
            id: $id,
            name: $attributes['name'],
            type: InstanceType::tryFrom($attributes['type']) ?? $attributes['type'],
            size: InstanceSize::tryFrom($attributes['size']) ?? $attributes['size'],
            scalingType: InstanceScalingType::tryFrom($attributes['scaling_type']) ?? $attributes['scaling_type'],
            minReplicas: $attributes['min_replicas'],
            maxReplicas: $attributes['max_replicas'],
            usesScheduler: $attributes['uses_scheduler'],
            scalingCpuThresholdPercentage: $attributes['scaling_cpu_threshold_percentage'] ?? null,
            scalingMemoryThresholdPercentage: $attributes['scaling_memory_threshold_percentage'] ?? null,
            backgroundProcesses: $backgroundProcesses,
            createdAt: isset($attributes['created_at'])
                ? CarbonImmutable::parse($attributes['created_at'])
                : null,
        );
    }
}
