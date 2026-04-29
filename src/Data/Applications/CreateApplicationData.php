<?php

namespace Redberry\LaravelCloudSdk\Data\Applications;

use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\SourceControlProvider;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class CreateApplicationData extends Data
{
    public function __construct(
        public string $repository,
        public string $name,
        public string|CloudRegion $region,
        public string|SourceControlProvider $sourceControlProviderType,
        public string|null|Optional $clusterId = new Optional,
    ) {}
}
