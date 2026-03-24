<?php

namespace Redberry\LaravelCloudSdk\Data\Instances;

use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapOutputName(SnakeCaseMapper::class)]
class BackgroundProcessConfigData extends Data
{
    public function __construct(
        public string|null|Optional $connection = new Optional,
        public string|null|Optional $queue = new Optional,
        public int|null|Optional $tries = new Optional,
        public int|null|Optional $backoff = new Optional,
        public int|null|Optional $sleep = new Optional,
        public int|null|Optional $rest = new Optional,
        public int|null|Optional $timeout = new Optional,
        public bool|Optional $force = new Optional,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            connection: $attributes['connection'] ?? new Optional,
            queue: $attributes['queue'] ?? new Optional,
            tries: $attributes['tries'] ?? new Optional,
            backoff: $attributes['backoff'] ?? new Optional,
            sleep: $attributes['sleep'] ?? new Optional,
            rest: $attributes['rest'] ?? new Optional,
            timeout: $attributes['timeout'] ?? new Optional,
            force: $attributes['force'] ?? new Optional,
        );
    }
}
