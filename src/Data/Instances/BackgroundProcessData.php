<?php

namespace App\Data\LaravelCloud\Instances;

use App\Enums\LaravelCloud\DaemonType;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapOutputName(SnakeCaseMapper::class)]
class BackgroundProcessData extends Data
{
    public function __construct(
        public string $id,
        public DaemonType $type,
        public int $processes,
        public string|null|Optional $command = new Optional,
        public BackgroundProcessConfigData|null|Optional $config = new Optional,
    ) {}

    public static function fromResponse(array $attributes, string $id): self
    {
        return new self(
            id: $id,
            type: DaemonType::from($attributes['type']),
            processes: $attributes['processes'],
            command: $attributes['command'] ?? null,
            config: isset($attributes['config'])
                ? BackgroundProcessConfigData::fromResponse($attributes['config'])
                : null,
        );
    }
}
