<?php

namespace App\Data\LaravelCloud\Caches;

use App\Enums\LaravelCloud\CacheProtocol;
use Spatie\LaravelData\Data;

class CacheConnectionData extends Data
{
    public function __construct(
        public ?string $hostname,
        public ?int $port,
        public CacheProtocol $protocol,
        public ?string $username,
        public ?string $password,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            hostname: $attributes['hostname'],
            port: $attributes['port'],
            protocol: CacheProtocol::from($attributes['protocol']),
            username: $attributes['username'],
            password: $attributes['password'],
        );
    }
}
