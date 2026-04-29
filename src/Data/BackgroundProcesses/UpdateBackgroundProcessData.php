<?php

namespace Redberry\LaravelCloudSdk\Data\BackgroundProcesses;

use Redberry\LaravelCloudSdk\Enums\DaemonType;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class UpdateBackgroundProcessData extends Data
{
    public function __construct(
        public string|DaemonType|Optional $type = new Optional,
        public int|Optional $processes = new Optional,
        public string|null|Optional $command = new Optional,
        public BackgroundProcessConfigData|null|Optional $config = new Optional,
    ) {}
}
