<?php

namespace Redberry\LaravelCloudSdk\Data\WebsocketClusters;

use Redberry\LaravelCloudSdk\Enums\WebsocketMaxConnections;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class UpdateWebsocketClusterData extends Data
{
    public function __construct(
        public string|Optional $name = new Optional,
        public string|WebsocketMaxConnections|Optional $maxConnections = new Optional,
    ) {}
}
