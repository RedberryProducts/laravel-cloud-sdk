<?php

namespace Redberry\LaravelCloudSdk\Support;

use Redberry\LaravelCloudSdk\Data\Applications\ApplicationData;
use Redberry\LaravelCloudSdk\Data\BackgroundProcesses\BackgroundProcessData;
use Redberry\LaravelCloudSdk\Data\Buckets\BucketData;
use Redberry\LaravelCloudSdk\Data\Buckets\BucketKeyData;
use Redberry\LaravelCloudSdk\Data\Caches\CacheData;
use Redberry\LaravelCloudSdk\Data\Commands\CommandData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseClusterData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseSnapshotData;
use Redberry\LaravelCloudSdk\Data\Databases\DatabaseData;
use Redberry\LaravelCloudSdk\Data\Deployments\DeploymentData;
use Redberry\LaravelCloudSdk\Data\Domains\DomainData;
use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentData;
use Redberry\LaravelCloudSdk\Data\Instances\InstanceData;
use Redberry\LaravelCloudSdk\Data\Meta\OrganizationData;
use Redberry\LaravelCloudSdk\Data\WebsocketApplications\WebsocketApplicationData;
use Redberry\LaravelCloudSdk\Data\WebsocketClusters\WebsocketClusterData;

class JsonApiTypeRegistry
{
    /** @var array<string, class-string> */
    private static array $types = [
        'applications' => ApplicationData::class,
        'organizations' => OrganizationData::class,
        'environments' => EnvironmentData::class,
        'instances' => InstanceData::class,
        'background_processes' => BackgroundProcessData::class,
        'deployments' => DeploymentData::class,
        'commands' => CommandData::class,
        'domains' => DomainData::class,
        'databases' => DatabaseClusterData::class,
        'databaseSchemas' => DatabaseData::class,
        'database_snapshots' => DatabaseSnapshotData::class,
        'caches' => CacheData::class,
        'filesystems' => BucketData::class,
        'filesystemKeys' => BucketKeyData::class,
        'websocketServers' => WebsocketClusterData::class,
        'websocketApplications' => WebsocketApplicationData::class,
    ];

    /**
     * API relationship names that differ from the DTO property name.
     *
     * @var array<class-string, array<string, string>>
     */
    private static array $propertyRenames = [
        DatabaseData::class => ['database' => 'databaseCluster'],
        DatabaseSnapshotData::class => ['database' => 'databaseCluster'],
        BucketKeyData::class => ['filesystem' => 'bucket'],
        WebsocketApplicationData::class => ['server' => 'websocketCluster'],
    ];

    /**
     * @return class-string|null
     */
    public static function dtoClass(string $type): ?string
    {
        return self::$types[$type] ?? null;
    }

    public static function propertyName(string $dtoClass, string $relationshipName): string
    {
        return self::$propertyRenames[$dtoClass][$relationshipName] ?? $relationshipName;
    }
}
