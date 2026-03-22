<?php

namespace App\Data\LaravelCloud\Instances;

use App\Enums\LaravelCloud\InstanceScalingType;
use App\Enums\LaravelCloud\InstanceSize;
use App\Enums\LaravelCloud\InstanceType;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;

class InstanceData extends Data
{
    /**
     * @param  BackgroundProcessData[]  $backgroundProcesses
     */
    public function __construct(
        public string $id,
        public string $name,
        public InstanceType $type,
        public InstanceSize $size,
        public InstanceScalingType $scalingType,
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
            type: InstanceType::from($attributes['type']),
            size: InstanceSize::from($attributes['size']),
            scalingType: InstanceScalingType::from($attributes['scaling_type']),
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
