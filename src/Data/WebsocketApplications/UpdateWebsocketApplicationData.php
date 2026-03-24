<?php

namespace App\Data\LaravelCloud\WebsocketApplications;

use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapOutputName(SnakeCaseMapper::class)]
class UpdateWebsocketApplicationData extends Data
{
    public function __construct(
        public string|Optional $name = new Optional,
        public int|Optional $pingInterval = new Optional,
        public int|Optional $activityTimeout = new Optional,
        public array|null|Optional $allowedOrigins = new Optional,
    ) {}
}
