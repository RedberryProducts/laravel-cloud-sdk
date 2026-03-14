<?php

namespace App\Data\LaravelCloud\DatabaseClusters;

use App\Enums\LaravelCloud\DatabaseDriver;
use App\Enums\LaravelCloud\DatabaseProtocol;
use Spatie\LaravelData\Data;

class DatabaseConnectionData extends Data
{
    public function __construct(
        public string $hostname,
        public int $port,
        public DatabaseProtocol $protocol,
        public DatabaseDriver $driver,
        public string $username,
        public string $password,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            hostname: $attributes['hostname'],
            port: $attributes['port'],
            protocol: DatabaseProtocol::from($attributes['protocol']),
            driver: DatabaseDriver::from($attributes['driver']),
            username: $attributes['username'],
            password: $attributes['password'],
        );
    }
}
