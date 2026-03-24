<?php

namespace Redberry\LaravelCloudSdk\Data\Caches;

use Redberry\LaravelCloudSdk\Enums\CacheProtocol;
use Spatie\LaravelData\Data;

class CacheConnectionData extends Data
{
    public function __construct(
        public ?string $hostname,
        public ?int $port,
        public string|CacheProtocol $protocol,
        public ?string $username,
        public ?string $password,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            hostname: $attributes['hostname'],
            port: $attributes['port'],
            protocol: CacheProtocol::tryFrom($attributes['protocol']) ?? $attributes['protocol'],
            username: $attributes['username'],
            password: $attributes['password'],
        );
    }
}
