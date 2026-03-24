<?php

namespace Redberry\LaravelCloudSdk\Data\Instances;

use Redberry\LaravelCloudSdk\Enums\DaemonType;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapOutputName(SnakeCaseMapper::class)]
class BackgroundProcessData extends Data
{
    public function __construct(
        public DaemonType $type,
        public int $processes,
        public string|null|Optional $command = new Optional,
        public BackgroundProcessConfigData|null|Optional $config = new Optional,
        public string|Optional $id = new Optional,
    ) {}

    public static function fromResponse(array $attributes, string $id): self
    {
        return new self(
            type: DaemonType::from($attributes['type']),
            processes: $attributes['processes'],
            command: $attributes['command'] ?? null,
            config: isset($attributes['config'])
                ? BackgroundProcessConfigData::fromResponse($attributes['config'])
                : null,
            id: $id,
        );
    }
}
