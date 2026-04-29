<?php

namespace Redberry\LaravelCloudSdk\Data\Environments;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class DeleteEnvironmentVariablesData extends Data
{
    /**
     * @param  array<int, string>  $keys
     */
    public function __construct(
        public array $keys,
    ) {}
}
