<?php

namespace Redberry\LaravelCloudSdk\Data\DatabaseClusters;

use Redberry\LaravelCloudSdk\Enums\NeonServerlessPostgresComputeUnit;
use Redberry\LaravelCloudSdk\Transformers\FloatBackedEnumTransformer;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapOutputName(SnakeCaseMapper::class)]
class NeonServerlessPostgresConfigData extends Data
{
    public function __construct(
        #[WithTransformer(FloatBackedEnumTransformer::class)]
        public float|NeonServerlessPostgresComputeUnit $cuMin,
        #[WithTransformer(FloatBackedEnumTransformer::class)]
        public float|NeonServerlessPostgresComputeUnit $cuMax,
        public int $suspendSeconds,
        public int $retentionDays,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            cuMin: NeonServerlessPostgresComputeUnit::tryFrom((string) $attributes['cu_min']) ?? (float) $attributes['cu_min'],
            cuMax: NeonServerlessPostgresComputeUnit::tryFrom((string) $attributes['cu_max']) ?? (float) $attributes['cu_max'],
            suspendSeconds: $attributes['suspend_seconds'],
            retentionDays: $attributes['retention_days'],
        );
    }
}
