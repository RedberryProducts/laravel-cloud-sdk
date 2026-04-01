<?php

namespace Redberry\LaravelCloudSdk\Data\Commands;

use Carbon\CarbonImmutable;
use Redberry\LaravelCloudSdk\Enums\CommandStatus;
use Spatie\LaravelData\Data;

class CommandData extends Data
{
    public function __construct(
        public string $id,
        public string $command,
        public ?string $output,
        public string|CommandStatus $status,
        public ?int $exitCode,
        public ?string $failureReason,
        public ?CarbonImmutable $startedAt,
        public ?CarbonImmutable $finishedAt,
        public ?CarbonImmutable $createdAt,
    ) {}

    public static function fromResponse(array $attributes, string $id): self
    {
        return new self(
            id: $id,
            command: $attributes['command'],
            output: $attributes['output'] ?? null,
            status: CommandStatus::tryFrom($attributes['status']) ?? $attributes['status'],
            exitCode: $attributes['exit_code'] ?? null,
            failureReason: $attributes['failure_reason'] ?? null,
            startedAt: isset($attributes['started_at']) ? CarbonImmutable::parse($attributes['started_at']) : null,
            finishedAt: isset($attributes['finished_at']) ? CarbonImmutable::parse($attributes['finished_at']) : null,
            createdAt: isset($attributes['created_at']) ? CarbonImmutable::parse($attributes['created_at']) : null,
        );
    }
}
