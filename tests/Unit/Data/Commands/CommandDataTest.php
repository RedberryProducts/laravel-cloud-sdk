<?php

use Carbon\CarbonImmutable;
use Redberry\LaravelCloudSdk\Data\Commands\CommandData;
use Redberry\LaravelCloudSdk\Enums\CommandStatus;

it('can be constructed from response attributes', function () {
    $dto = CommandData::fromResponse([
        'command' => 'php artisan migrate:status',
        'output' => 'Ran All Migrations',
        'status' => 'command.success',
        'exit_code' => 0,
        'failure_reason' => null,
        'started_at' => '2026-01-01T00:00:00.000000Z',
        'finished_at' => '2026-01-01T00:00:05.000000Z',
        'created_at' => '2026-01-01T00:00:00.000000Z',
    ], 'cmd-abc123');

    expect($dto->id)->toBe('cmd-abc123');
    expect($dto->command)->toBe('php artisan migrate:status');
    expect($dto->output)->toBe('Ran All Migrations');
    expect($dto->status)->toBe(CommandStatus::Success);
    expect($dto->exitCode)->toBe(0);
    expect($dto->failureReason)->toBeNull();
    expect($dto->startedAt)->toBeInstanceOf(CarbonImmutable::class);
    expect($dto->finishedAt)->toBeInstanceOf(CarbonImmutable::class);
    expect($dto->createdAt)->toBeInstanceOf(CarbonImmutable::class);
});

it('handles null optional fields', function () {
    $dto = CommandData::fromResponse([
        'command' => 'php artisan cache:clear',
        'status' => 'pending',
    ], 'cmd-xyz');

    expect($dto->output)->toBeNull();
    expect($dto->exitCode)->toBeNull();
    expect($dto->failureReason)->toBeNull();
    expect($dto->startedAt)->toBeNull();
    expect($dto->finishedAt)->toBeNull();
    expect($dto->createdAt)->toBeNull();
});

it('falls back to raw string for unknown status', function () {
    $dto = CommandData::fromResponse([
        'command' => 'php artisan cache:clear',
        'status' => 'unknown-status',
    ], 'cmd-xyz');

    expect($dto->status)->toBe('unknown-status');
});
