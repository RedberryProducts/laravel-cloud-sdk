<?php

namespace Redberry\LaravelCloudSdk\Data\BackgroundProcesses;

use Redberry\LaravelCloudSdk\Data\Instances\BackgroundProcessConfigData;
use Redberry\LaravelCloudSdk\Enums\DaemonType;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapOutputName(SnakeCaseMapper::class)]
class CreateBackgroundProcessData extends Data
{
    public function __construct(
        public string|DaemonType $type,
        public int $processes,
        public string|null|Optional $command = new Optional,
        public BackgroundProcessConfigData|null|Optional $config = new Optional,
    ) {}
}
