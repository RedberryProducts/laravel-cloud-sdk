<?php

namespace Redberry\LaravelCloudSdk\Data\Environments;

use Carbon\CarbonImmutable;
use Redberry\LaravelCloudSdk\Enums\EnvironmentStatus;
use Redberry\LaravelCloudSdk\Enums\NodeVersion;
use Redberry\LaravelCloudSdk\Enums\PhpVersion;
use Spatie\LaravelData\Data;

class EnvironmentData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $slug,
        public string|EnvironmentStatus $status,
        public string|PhpVersion $phpMajorVersion,
        public string|NodeVersion $nodeVersion,
        public string $vanityDomain,
        public bool $createdFromAutomation,
        public bool $usesOctane,
        public bool $usesHibernation,
        public bool $usesPushToDeploy,
        public bool $usesDeployHook,
        public ?string $buildCommand,
        public ?string $deployCommand,
        /** @var EnvironmentVariableData[] */
        public array $environmentVariables,
        public NetworkSettingsData $networkSettings,
        public ?CarbonImmutable $createdAt,
    ) {}

    public static function fromResponse(array $attributes, string $id): self
    {
        return new self(
            id: $id,
            name: $attributes['name'],
            slug: $attributes['slug'],
            status: EnvironmentStatus::tryFrom($attributes['status']) ?? $attributes['status'],
            phpMajorVersion: PhpVersion::tryFrom($attributes['php_major_version']) ?? $attributes['php_major_version'],
            nodeVersion: NodeVersion::tryFrom($attributes['node_version']) ?? $attributes['node_version'],
            vanityDomain: $attributes['vanity_domain'],
            createdFromAutomation: $attributes['created_from_automation'],
            usesOctane: $attributes['uses_octane'],
            usesHibernation: $attributes['uses_hibernation'],
            usesPushToDeploy: $attributes['uses_push_to_deploy'],
            usesDeployHook: $attributes['uses_deploy_hook'],
            buildCommand: $attributes['build_command'] ?? null,
            deployCommand: $attributes['deploy_command'] ?? null,
            environmentVariables: array_map(
                fn (array $var) => EnvironmentVariableData::fromResponse($var),
                $attributes['environment_variables'] ?? [],
            ),
            networkSettings: NetworkSettingsData::fromResponse($attributes['network_settings']),
            createdAt: isset($attributes['created_at'])
                ? CarbonImmutable::parse($attributes['created_at'])
                : null,
        );
    }
}
