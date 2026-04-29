<?php

namespace Redberry\LaravelCloudSdk\Data\WebsocketApplications;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class UpdateWebsocketApplicationData extends Data
{
    public function __construct(
        public string|Optional $name = new Optional,
        public int|Optional $pingInterval = new Optional,
        public int|Optional $activityTimeout = new Optional,
        public array|null|Optional $allowedOrigins = new Optional,
    ) {}
}
