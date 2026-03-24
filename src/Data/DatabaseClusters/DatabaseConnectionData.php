<?php

namespace Redberry\LaravelCloudSdk\Data\DatabaseClusters;

use Redberry\LaravelCloudSdk\Enums\DatabaseDriver;
use Redberry\LaravelCloudSdk\Enums\DatabaseProtocol;
use Spatie\LaravelData\Data;

class DatabaseConnectionData extends Data
{
    public function __construct(
        public string $hostname,
        public int $port,
        public string|DatabaseProtocol $protocol,
        public string|DatabaseDriver $driver,
        public string $username,
        public string $password,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            hostname: $attributes['hostname'],
            port: $attributes['port'],
            protocol: DatabaseProtocol::tryFrom($attributes['protocol']) ?? $attributes['protocol'],
            driver: DatabaseDriver::tryFrom($attributes['driver']) ?? $attributes['driver'],
            username: $attributes['username'],
            password: $attributes['password'],
        );
    }
}
