<?php

namespace App\Data\LaravelCloud\WebsocketApplications;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;

class WebsocketApplicationData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $appId,
        public array $allowedOrigins,
        public int $pingInterval,
        public int $activityTimeout,
        public int $maxMessageSize,
        public int $maxConnections,
        public string $key,
        public string $secret,
        public ?CarbonImmutable $createdAt,
    ) {}

    public static function fromResponse(array $attributes, string $id): self
    {
        return new self(
            id: $id,
            name: $attributes['name'],
            appId: $attributes['app_id'],
            allowedOrigins: $attributes['allowed_origins'] ?? [],
            pingInterval: $attributes['ping_interval'],
            activityTimeout: $attributes['activity_timeout'],
            maxMessageSize: $attributes['max_message_size'],
            maxConnections: $attributes['max_connections'],
            key: $attributes['key'],
            secret: $attributes['secret'],
            createdAt: isset($attributes['created_at'])
                ? CarbonImmutable::parse($attributes['created_at'])
                : null,
        );
    }
}
