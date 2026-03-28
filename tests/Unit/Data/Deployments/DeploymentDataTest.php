<?php

use Carbon\CarbonImmutable;
use Redberry\LaravelCloudSdk\Data\Deployments\DeploymentData;
use Redberry\LaravelCloudSdk\Enums\DeploymentStatus;
use Redberry\LaravelCloudSdk\Enums\NodeVersion;
use Redberry\LaravelCloudSdk\Enums\PhpVersion;

it('can be constructed from response attributes', function () {
    $dto = DeploymentData::fromResponse([
        'status' => 'deployment.succeeded',
        'branch_name' => 'main',
        'commit_hash' => 'abc123',
        'commit_message' => 'Fix bug',
        'commit_author' => 'Jane',
        'failure_reason' => null,
        'php_major_version' => '8.4',
        'build_command' => 'npm run build',
        'node_version' => '22',
        'uses_octane' => false,
        'uses_hibernation' => true,
        'started_at' => '2026-01-01T00:00:00.000000Z',
        'finished_at' => '2026-01-01T00:05:00.000000Z',
        'created_at' => '2026-01-01T00:00:00.000000Z',
    ], 'deploy-abc123');

    expect($dto->id)->toBe('deploy-abc123');
    expect($dto->status)->toBe(DeploymentStatus::DeploymentSucceeded);
    expect($dto->branchName)->toBe('main');
    expect($dto->commitHash)->toBe('abc123');
    expect($dto->commitMessage)->toBe('Fix bug');
    expect($dto->commitAuthor)->toBe('Jane');
    expect($dto->failureReason)->toBeNull();
    expect($dto->phpMajorVersion)->toBe(PhpVersion::V8_4);
    expect($dto->buildCommand)->toBe('npm run build');
    expect($dto->nodeVersion)->toBe(NodeVersion::V22);
    expect($dto->usesOctane)->toBeFalse();
    expect($dto->usesHibernation)->toBeTrue();
    expect($dto->startedAt)->toBeInstanceOf(CarbonImmutable::class);
    expect($dto->finishedAt)->toBeInstanceOf(CarbonImmutable::class);
    expect($dto->createdAt)->toBeInstanceOf(CarbonImmutable::class);
});

it('handles null optional fields', function () {
    $dto = DeploymentData::fromResponse([
        'status' => 'pending',
        'uses_octane' => false,
        'uses_hibernation' => false,
    ], 'deploy-xyz');

    expect($dto->branchName)->toBeNull();
    expect($dto->commitHash)->toBeNull();
    expect($dto->phpMajorVersion)->toBeNull();
    expect($dto->nodeVersion)->toBeNull();
    expect($dto->startedAt)->toBeNull();
    expect($dto->finishedAt)->toBeNull();
    expect($dto->createdAt)->toBeNull();
});

it('handles pending status', function () {
    $dto = DeploymentData::fromResponse([
        'status' => 'pending',
        'uses_octane' => false,
        'uses_hibernation' => false,
    ], 'deploy-xyz');

    expect($dto->status)->toBe(DeploymentStatus::Pending);
});

it('falls back to raw string for unknown status', function () {
    $dto = DeploymentData::fromResponse([
        'status' => 'unknown-status',
        'uses_octane' => false,
        'uses_hibernation' => false,
    ], 'deploy-xyz');

    expect($dto->status)->toBe('unknown-status');
});
