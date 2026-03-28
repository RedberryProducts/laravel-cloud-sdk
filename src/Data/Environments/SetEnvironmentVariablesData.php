<?php

namespace Redberry\LaravelCloudSdk\Data\Environments;

use Redberry\LaravelCloudSdk\Enums\EnvironmentVariableMethod;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapOutputName(SnakeCaseMapper::class)]
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
