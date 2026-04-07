<?php

namespace Redberry\LaravelCloudSdk\Resources;

use Illuminate\Support\LazyCollection;
use Redberry\LaravelCloudSdk\Data\Environments\CreateEnvironmentData;
use Redberry\LaravelCloudSdk\Data\Environments\DeleteEnvironmentVariablesData;
use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentData;
use Redberry\LaravelCloudSdk\Data\Environments\HstsData;
use Redberry\LaravelCloudSdk\Data\Environments\SetEnvironmentVariablesData;
use Redberry\LaravelCloudSdk\Data\Environments\UpdateEnvironmentData;
use Redberry\LaravelCloudSdk\Enums\CacheStrategy;
use Redberry\LaravelCloudSdk\Enums\EnvironmentColor;
use Redberry\LaravelCloudSdk\Enums\EnvironmentVariableMethod;
use Redberry\LaravelCloudSdk\Enums\FirewallRateLimitLevel;
use Redberry\LaravelCloudSdk\Enums\NodeVersion;
use Redberry\LaravelCloudSdk\Enums\PhpVersion;
use Redberry\LaravelCloudSdk\Enums\ResponseHeadersContentType;
use Redberry\LaravelCloudSdk\Enums\ResponseHeadersFrame;
use Redberry\LaravelCloudSdk\Enums\ResponseHeadersRobotsTag;
use Redberry\LaravelCloudSdk\Requests\Environments\CreateEnvironmentRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\DeleteEnvironmentRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\DeleteEnvironmentVariablesRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\GetEnvironmentRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\SetEnvironmentVariablesRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\UpdateEnvironmentRequest;
use Spatie\LaravelData\Optional;

trait ManagesEnvironments
{
    /**
     * @return LazyCollection<int, EnvironmentData>
     */
    public function environments(string $applicationId): LazyCollection
    {
        return $this->connector->paginate(new ListEnvironmentsRequest($applicationId))->collect();
    }

    public function environment(string $id): EnvironmentData
    {
        return $this->connector->send(new GetEnvironmentRequest($id))->dtoOrFail();
    }

    public function createEnvironment(
        string $applicationId,
        string $branch,
        string $name,
        string|null|Optional $clusterId = new Optional,
    ): EnvironmentData {
        return $this->createEnvironmentWith($applicationId, new CreateEnvironmentData(
            branch: $branch,
            name: $name,
            clusterId: $clusterId,
        ));
    }

    public function createEnvironmentWith(string $applicationId, CreateEnvironmentData $data): EnvironmentData
    {
        return $this->connector->send(new CreateEnvironmentRequest($applicationId, $data))->dtoOrFail();
    }

    public function updateEnvironment(
        string $id,
        string|Optional $name = new Optional,
        string|Optional $slug = new Optional,
        string|EnvironmentColor|Optional $color = new Optional,
        string|Optional $branch = new Optional,
        string|PhpVersion|Optional $phpVersion = new Optional,
        string|NodeVersion|Optional $nodeVersion = new Optional,
        string|null|Optional $buildCommand = new Optional,
        string|null|Optional $deployCommand = new Optional,
        bool|Optional $usesPushToDeploy = new Optional,
        bool|Optional $usesDeployHook = new Optional,
        bool|Optional $usesOctane = new Optional,
        bool|Optional $usesVanityDomain = new Optional,
        int|Optional $timeout = new Optional,
        int|Optional $sleepTimeout = new Optional,
        int|Optional $shutdownTimeout = new Optional,
        bool|Optional $usesPurgeEdgeCacheOnDeploy = new Optional,
        string|null|Optional $nightwatchToken = new Optional,
        string|CacheStrategy|Optional $cacheStrategy = new Optional,
        string|ResponseHeadersFrame|Optional $responseHeadersFrame = new Optional,
        string|ResponseHeadersContentType|Optional $responseHeadersContentType = new Optional,
        string|ResponseHeadersRobotsTag|Optional $responseHeadersRobotsTag = new Optional,
        HstsData|null|Optional $responseHeadersHsts = new Optional,
        array|null|Optional $filesystemKeys = new Optional,
        string|FirewallRateLimitLevel|null|Optional $firewallRateLimitLevel = new Optional,
        bool|Optional $firewallUnderAttackMode = new Optional,
        string|null|Optional $databaseSchemaId = new Optional,
        string|null|Optional $cacheId = new Optional,
        string|null|Optional $websocketApplicationId = new Optional,
    ): EnvironmentData {
        return $this->updateEnvironmentWith($id, new UpdateEnvironmentData(
            name: $name,
            slug: $slug,
            color: $color,
            branch: $branch,
            phpVersion: $phpVersion,
            nodeVersion: $nodeVersion,
            buildCommand: $buildCommand,
            deployCommand: $deployCommand,
            usesPushToDeploy: $usesPushToDeploy,
            usesDeployHook: $usesDeployHook,
            usesOctane: $usesOctane,
            usesVanityDomain: $usesVanityDomain,
            timeout: $timeout,
            sleepTimeout: $sleepTimeout,
            shutdownTimeout: $shutdownTimeout,
            usesPurgeEdgeCacheOnDeploy: $usesPurgeEdgeCacheOnDeploy,
            nightwatchToken: $nightwatchToken,
            cacheStrategy: $cacheStrategy,
            responseHeadersFrame: $responseHeadersFrame,
            responseHeadersContentType: $responseHeadersContentType,
            responseHeadersRobotsTag: $responseHeadersRobotsTag,
            responseHeadersHsts: $responseHeadersHsts,
            filesystemKeys: $filesystemKeys,
            firewallRateLimitLevel: $firewallRateLimitLevel,
            firewallUnderAttackMode: $firewallUnderAttackMode,
            databaseSchemaId: $databaseSchemaId,
            cacheId: $cacheId,
            websocketApplicationId: $websocketApplicationId,
        ));
    }

    public function updateEnvironmentWith(string $id, UpdateEnvironmentData $data): EnvironmentData
    {
        return $this->connector->send(new UpdateEnvironmentRequest($id, $data))->dtoOrFail();
    }

    public function deleteEnvironment(string $id): void
    {
        $this->connector->send(new DeleteEnvironmentRequest($id))->throw();
    }

    public function setEnvironmentVariables(
        string $environmentId,
        string|EnvironmentVariableMethod $method,
        array $variables,
    ): EnvironmentData {
        return $this->setEnvironmentVariablesWith($environmentId, new SetEnvironmentVariablesData(
            method: $method,
            variables: $variables,
        ));
    }

    public function setEnvironmentVariablesWith(string $environmentId, SetEnvironmentVariablesData $data): EnvironmentData
    {
        return $this->connector->send(new SetEnvironmentVariablesRequest($environmentId, $data))->dtoOrFail();
    }

    public function deleteEnvironmentVariables(string $environmentId, array $keys): EnvironmentData
    {
        return $this->deleteEnvironmentVariablesWith($environmentId, new DeleteEnvironmentVariablesData(
            keys: $keys,
        ));
    }

    public function deleteEnvironmentVariablesWith(string $environmentId, DeleteEnvironmentVariablesData $data): EnvironmentData
    {
        return $this->connector->send(new DeleteEnvironmentVariablesRequest($environmentId, $data))->dtoOrFail();
    }
}
