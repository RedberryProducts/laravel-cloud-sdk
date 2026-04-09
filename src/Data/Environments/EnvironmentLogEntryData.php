<?php

namespace Redberry\LaravelCloudSdk\Data\Environments;

use Redberry\LaravelCloudSdk\Enums\LogEntryType;
use Redberry\LaravelCloudSdk\Enums\LogLevel;
use Spatie\LaravelData\Data;

class EnvironmentLogEntryData extends Data
{
    public function __construct(
        public string $message,
        public string|LogLevel $level,
        public string|LogEntryType $type,
        public string $loggedAt,
        public ?array $data,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            message: $attributes['message'],
            level: LogLevel::tryFrom($attributes['level']) ?? $attributes['level'],
            type: LogEntryType::tryFrom($attributes['type']) ?? $attributes['type'],
            loggedAt: $attributes['logged_at'],
            data: $attributes['data'] ?? null,
        );
    }
}
