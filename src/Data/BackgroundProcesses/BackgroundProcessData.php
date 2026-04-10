<?php

namespace Redberry\LaravelCloudSdk\Data\BackgroundProcesses;

use Carbon\CarbonImmutable;
use Redberry\LaravelCloudSdk\Enums\DaemonStrategyType;
use Redberry\LaravelCloudSdk\Enums\DaemonType;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapOutputName(SnakeCaseMapper::class)]
class BackgroundProcessData extends Data
{
    public function __construct(
        public string|DaemonType $type,
        public int $processes,
        public string|null|Optional $command = new Optional,
        public BackgroundProcessConfigData|null|Optional $config = new Optional,
        public string|DaemonStrategyType|Optional $strategyType = new Optional,
        public int|null|Optional $strategyThreshold = new Optional,
        public CarbonImmutable|null|Optional $createdAt = new Optional,
        public string|Optional $id = new Optional,
    ) {}

    public static function fromResponse(array $attributes, string $id): self
    {
        return new self(
            type: DaemonType::tryFrom($attributes['type']) ?? $attributes['type'],
            processes: $attributes['processes'],
            command: $attributes['command'] ?? null,
            config: isset($attributes['config'])
                ? BackgroundProcessConfigData::fromResponse($attributes['config'])
                : null,
            strategyType: isset($attributes['strategy_type'])
                ? (DaemonStrategyType::tryFrom($attributes['strategy_type']) ?? $attributes['strategy_type'])
                : new Optional,
            strategyThreshold: $attributes['strategy_threshold'] ?? new Optional,
            createdAt: isset($attributes['created_at'])
                ? CarbonImmutable::parse($attributes['created_at'])
                : null,
            id: $id,
        );
    }
}
