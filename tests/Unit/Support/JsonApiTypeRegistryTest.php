<?php

use Redberry\LaravelCloudSdk\Data\Applications\ApplicationData;
use Redberry\LaravelCloudSdk\Data\BackgroundProcesses\BackgroundProcessData;
use Redberry\LaravelCloudSdk\Data\Branches\BranchData;
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
use Redberry\LaravelCloudSdk\Data\Users\UserData;
use Redberry\LaravelCloudSdk\Data\WebsocketApplications\WebsocketApplicationData;
use Redberry\LaravelCloudSdk\Data\WebsocketClusters\WebsocketClusterData;
use Redberry\LaravelCloudSdk\Support\JsonApiTypeRegistry;

it('maps all type strings to correct DTO classes', function (string $type, string $dtoClass) {
    expect(JsonApiTypeRegistry::dtoClass($type))->toBe($dtoClass);
})->with([
    ['applications', ApplicationData::class],
    ['organizations', OrganizationData::class],
    ['environments', EnvironmentData::class],
    ['branches', BranchData::class],
    ['instances', InstanceData::class],
    ['background_processes', BackgroundProcessData::class],
    ['deployments', DeploymentData::class],
    ['commands', CommandData::class],
    ['domains', DomainData::class],
    ['users', UserData::class],
    ['databases', DatabaseClusterData::class],
    ['databaseSchemas', DatabaseData::class],
    ['database_snapshots', DatabaseSnapshotData::class],
    ['caches', CacheData::class],
    ['filesystems', BucketData::class],
    ['filesystemKeys', BucketKeyData::class],
    ['websocketServers', WebsocketClusterData::class],
    ['websocketApplications', WebsocketApplicationData::class],
]);

it('returns null for unknown type string', function () {
    expect(JsonApiTypeRegistry::dtoClass('unknown_type'))->toBeNull();
});

it('returns the relationship name as property name by default', function () {
    expect(JsonApiTypeRegistry::propertyName(EnvironmentData::class, 'application'))->toBe('application');
});

it('returns renamed property names', function (string $dtoClass, string $relationshipName, string $expectedProperty) {
    expect(JsonApiTypeRegistry::propertyName($dtoClass, $relationshipName))->toBe($expectedProperty);
})->with([
    [DatabaseData::class, 'database', 'databaseCluster'],
    [DatabaseSnapshotData::class, 'database', 'databaseCluster'],
    [BucketKeyData::class, 'filesystem', 'bucket'],
    [WebsocketApplicationData::class, 'server', 'websocketCluster'],
]);
