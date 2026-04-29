<?php

namespace Redberry\LaravelCloudSdk\Data\Environments;

use Redberry\LaravelCloudSdk\Enums\EnvironmentVariableMethod;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class SetEnvironmentVariablesData extends Data
{
    /**
     * @param  array<int, EnvironmentVariableData>  $variables
     */
    public function __construct(
        public string|EnvironmentVariableMethod $method,
        public array $variables,
    ) {}
}
