<?php

namespace App\Data\LaravelCloud\Instances;

use App\Enums\LaravelCloud\InstanceScalingType;
use App\Enums\LaravelCloud\InstanceSize;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapOutputName(SnakeCaseMapper::class)]
class UpdateInstanceData extends Data
{
    public function __construct(
        public string|Optional $name = new Optional,
        public InstanceSize|Optional $size = new Optional,
        public InstanceScalingType|Optional $scalingType = new Optional,
        public int|Optional $maxReplicas = new Optional,
        public int|Optional $minReplicas = new Optional,
        public bool|Optional $usesSleepMode = new Optional,
        public int|Optional $sleepTimeout = new Optional,
        public bool|Optional $usesScheduler = new Optional,
        public bool|Optional $usesOctane = new Optional,
        public bool|Optional $usesInertiaSsr = new Optional,
        public int|null|Optional $scalingCpuThresholdPercentage = new Optional,
        public int|null|Optional $scalingMemoryThresholdPercentage = new Optional,
    ) {}
}
