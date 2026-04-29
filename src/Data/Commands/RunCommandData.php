<?php

namespace Redberry\LaravelCloudSdk\Data\Commands;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class RunCommandData extends Data
{
    public function __construct(
        public string $command,
    ) {}
}
