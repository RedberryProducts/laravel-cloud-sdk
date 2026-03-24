<?php

namespace Redberry\LaravelCloudSdk\Data\Applications;

use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\SourceControlProvider;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapOutputName(SnakeCaseMapper::class)]
class CreateApplicationData extends Data
{
    public function __construct(
        public string $repository,
        public string $name,
        public CloudRegion $region,
        public SourceControlProvider $sourceControlProviderType,
        public string|null|Optional $clusterId = new Optional,
    ) {}
}
