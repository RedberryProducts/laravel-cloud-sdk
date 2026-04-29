<?php

namespace Redberry\LaravelCloudSdk\Data\Applications;

use Redberry\LaravelCloudSdk\Enums\SourceControlProvider;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class UpdateApplicationData extends Data
{
    public function __construct(
        public string|SourceControlProvider|Optional $sourceControlProviderType = new Optional,
        public string|Optional $name = new Optional,
        public string|Optional $slug = new Optional,
        public string|Optional $defaultEnvironmentId = new Optional,
        public string|Optional $repository = new Optional,
        public string|null|Optional $slackChannel = new Optional,
    ) {}
}
