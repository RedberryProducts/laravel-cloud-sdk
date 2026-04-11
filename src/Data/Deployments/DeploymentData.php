<?php

namespace Redberry\LaravelCloudSdk\Data\Deployments;

use Carbon\CarbonImmutable;
use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentData;
use Redberry\LaravelCloudSdk\Data\Users\UserData;
use Redberry\LaravelCloudSdk\Enums\DeploymentStatus;
use Redberry\LaravelCloudSdk\Enums\NodeVersion;
use Redberry\LaravelCloudSdk\Enums\PhpVersion;
use Spatie\LaravelData\Data;

class DeploymentData extends Data
{
    public function __construct(
        public string $id,
        public string|DeploymentStatus $status,
        public ?string $branchName,
        public ?string $commitHash,
        public ?string $commitMessage,
        public ?string $commitAuthor,
        public ?string $failureReason,
        public null|string|PhpVersion $phpMajorVersion,
        public ?string $buildCommand,
        public null|string|NodeVersion $nodeVersion,
        public bool $usesOctane,
        public bool $usesHibernation,
        public ?CarbonImmutable $startedAt,
        public ?CarbonImmutable $finishedAt,
        public ?CarbonImmutable $createdAt,
        public ?EnvironmentData $environment = null,
        public ?UserData $initiator = null,
    ) {}

    public static function fromResponse(array $attributes, string $id): self
    {
        return new self(
            id: $id,
            status: DeploymentStatus::tryFrom($attributes['status']) ?? $attributes['status'],
            branchName: $attributes['branch_name'] ?? null,
            commitHash: $attributes['commit_hash'] ?? null,
            commitMessage: $attributes['commit_message'] ?? null,
            commitAuthor: $attributes['commit_author'] ?? null,
            failureReason: $attributes['failure_reason'] ?? null,
            phpMajorVersion: isset($attributes['php_major_version'])
                ? (PhpVersion::tryFrom($attributes['php_major_version']) ?? $attributes['php_major_version'])
                : null,
            buildCommand: $attributes['build_command'] ?? null,
            nodeVersion: isset($attributes['node_version'])
                ? (NodeVersion::tryFrom($attributes['node_version']) ?? $attributes['node_version'])
                : null,
            usesOctane: $attributes['uses_octane'] ?? false,
            usesHibernation: $attributes['uses_hibernation'] ?? false,
            startedAt: isset($attributes['started_at']) ? CarbonImmutable::parse($attributes['started_at']) : null,
            finishedAt: isset($attributes['finished_at']) ? CarbonImmutable::parse($attributes['finished_at']) : null,
            createdAt: isset($attributes['created_at']) ? CarbonImmutable::parse($attributes['created_at']) : null,
        );
    }
}
