<?php

namespace App\Data\LaravelCloud\Applications;

use App\Enums\VcsProviderEnum;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapOutputName(SnakeCaseMapper::class)]
class UpdateApplicationData extends Data
{
    public function __construct(
        public VcsProviderEnum|Optional $sourceControlProviderType = new Optional,
        public string|Optional $name = new Optional,
        public string|Optional $slug = new Optional,
        public string|Optional $defaultEnvironmentId = new Optional,
        public string|Optional $repository = new Optional,
        public string|null|Optional $slackChannel = new Optional,
    ) {}
}
