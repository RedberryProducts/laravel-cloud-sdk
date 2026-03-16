<?php

namespace App\Data\LaravelCloud\Applications;

use App\Enums\LaravelCloud\CloudRegion;
use App\Enums\VcsProviderEnum;
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
        public VcsProviderEnum $sourceControlProviderType,
        public string|null|Optional $clusterId = new Optional,
    ) {}
}
