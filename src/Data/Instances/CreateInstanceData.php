<?php

namespace Redberry\LaravelCloudSdk\Data\Instances;

use Redberry\LaravelCloudSdk\Enums\InstanceScalingType;
use Redberry\LaravelCloudSdk\Enums\InstanceSize;
use Redberry\LaravelCloudSdk\Enums\InstanceType;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapOutputName(SnakeCaseMapper::class)]
class CreateInstanceData extends Data
{
    /**
     * @param  BackgroundProcessData[]|Optional  $backgroundProcesses
     */
    public function __construct(
        public string $name,
        public InstanceType $type,
        public InstanceSize $size,
        public InstanceScalingType $scalingType,
        public int $maxReplicas,
        public int $minReplicas,
        public bool|Optional $usesScheduler = new Optional,
        public int|null|Optional $scalingCpuThresholdPercentage = new Optional,
        public int|null|Optional $scalingMemoryThresholdPercentage = new Optional,
        public array|Optional $backgroundProcesses = new Optional,
    ) {}
}
