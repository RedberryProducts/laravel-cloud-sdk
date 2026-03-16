<?php

namespace App\Data\LaravelCloud\WebsocketClusters;

use App\Enums\LaravelCloud\CloudRegion;
use App\Enums\LaravelCloud\WebsocketMaxConnections;
use App\Enums\LaravelCloud\WebsocketServerType;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapOutputName(SnakeCaseMapper::class)]
class CreateWebsocketClusterData extends Data
{
    public function __construct(
        public string $name,
        public WebsocketServerType $type,
        public CloudRegion $region,
        public WebsocketMaxConnections $maxConnections,
    ) {}
}
