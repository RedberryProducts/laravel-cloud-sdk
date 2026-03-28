<?php

namespace Redberry\LaravelCloudSdk\Data\Environments;

use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapOutputName(SnakeCaseMapper::class)]
class DeleteEnvironmentVariablesData extends Data
{
    /**
     * @param  array<int, string>  $keys
     */
    public function __construct(
        public array $keys,
    ) {}
}
