<?php

namespace Redberry\LaravelCloudSdk\Data\Commands;

use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapOutputName(SnakeCaseMapper::class)]
class RunCommandData extends Data
{
    public function __construct(
        public string $command,
    ) {}
}
