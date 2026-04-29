<?php

namespace Redberry\LaravelCloudSdk\Data\Environments;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class CreateEnvironmentData extends Data
{
    public function __construct(
        public string $branch,
        public string $name,
        public string|null|Optional $clusterId = new Optional,
    ) {}
}
