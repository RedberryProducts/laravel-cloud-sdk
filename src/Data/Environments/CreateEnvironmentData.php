<?php

namespace App\Data\LaravelCloud\Environments;

use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapOutputName(SnakeCaseMapper::class)]
class CreateEnvironmentData extends Data
{
    public function __construct(
        public string $branch,
        public string $name,
        public string|null|Optional $clusterId = new Optional,
    ) {}
}
