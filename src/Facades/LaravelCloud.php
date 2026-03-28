<?php

namespace Redberry\LaravelCloudSdk\Facades;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Redberry\LaravelCloudSdk\Data\Applications\ApplicationData;
use Redberry\LaravelCloudSdk\Data\Applications\CreateApplicationData;
use Redberry\LaravelCloudSdk\Data\Applications\UpdateApplicationData;
use Redberry\LaravelCloudSdk\Data\Buckets\BucketData;
use Redberry\LaravelCloudSdk\Data\Buckets\BucketKeyData;
use Redberry\LaravelCloudSdk\Data\Buckets\CreateBucketData;
use Redberry\LaravelCloudSdk\Data\Buckets\CreateBucketKeyData;
use Redberry\LaravelCloudSdk\Data\Buckets\UpdateBucketData;
use Redberry\LaravelCloudSdk\Data\Buckets\UpdateBucketKeyData;
use Redberry\LaravelCloudSdk\Data\Caches\CacheData;
use Redberry\LaravelCloudSdk\Data\Caches\CreateCacheData;
use Redberry\LaravelCloudSdk\Data\Caches\UpdateCacheData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\AwsRdsConfigData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\CreateDatabaseClusterData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseClusterData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\LaravelMysqlConfigData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\NeonConfigData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\UpdateDatabaseClusterData;
use Redberry\LaravelCloudSdk\Data\Databases\CreateDatabaseData;
use Redberry\LaravelCloudSdk\Data\Databases\DatabaseData;
use Redberry\LaravelCloudSdk\Data\Domains\CreateDomainData;
use Redberry\LaravelCloudSdk\Data\Domains\DomainData;
use Redberry\LaravelCloudSdk\Data\Domains\UpdateDomainData;
use Redberry\LaravelCloudSdk\Data\Environments\CreateEnvironmentData;
use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentData;
use Redberry\LaravelCloudSdk\Data\Environments\HstsData;
use Redberry\LaravelCloudSdk\Data\Environments\UpdateEnvironmentData;
use Redberry\LaravelCloudSdk\Data\Instances\CreateInstanceData;
use Redberry\LaravelCloudSdk\Data\Instances\InstanceData;
use Redberry\LaravelCloudSdk\Data\Instances\UpdateInstanceData;
use Redberry\LaravelCloudSdk\Data\WebsocketApplications\CreateWebsocketApplicationData;
use Redberry\LaravelCloudSdk\Data\WebsocketApplications\UpdateWebsocketApplicationData;
use Redberry\LaravelCloudSdk\Data\WebsocketApplications\WebsocketApplicationData;
use Redberry\LaravelCloudSdk\Data\WebsocketClusters\CreateWebsocketClusterData;
use Redberry\LaravelCloudSdk\Data\WebsocketClusters\UpdateWebsocketClusterData;
use Redberry\LaravelCloudSdk\Data\WebsocketClusters\WebsocketClusterData;
use Redberry\LaravelCloudSdk\Enums\BucketJurisdiction;
use Redberry\LaravelCloudSdk\Enums\BucketVisibility;
use Redberry\LaravelCloudSdk\Enums\CacheSize;
use Redberry\LaravelCloudSdk\Enums\CacheStrategy;
use Redberry\LaravelCloudSdk\Enums\CacheType;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\DatabaseType;
use Redberry\LaravelCloudSdk\Enums\DomainCloudflareStrategy;
use Redberry\LaravelCloudSdk\Enums\DomainRedirect;
use Redberry\LaravelCloudSdk\Enums\DomainVerificationMethod;
use Redberry\LaravelCloudSdk\Enums\EnvironmentColor;
use Redberry\LaravelCloudSdk\Enums\EvictionPolicy;
use Redberry\LaravelCloudSdk\Enums\FirewallRateLimitLevel;
use Redberry\LaravelCloudSdk\Enums\InstanceScalingType;
use Redberry\LaravelCloudSdk\Enums\InstanceSize;
use Redberry\LaravelCloudSdk\Enums\InstanceType;
use Redberry\LaravelCloudSdk\Enums\KeyPermission;
use Redberry\LaravelCloudSdk\Enums\NodeVersion;
use Redberry\LaravelCloudSdk\Enums\PhpVersion;
use Redberry\LaravelCloudSdk\Enums\ResponseHeadersContentType;
use Redberry\LaravelCloudSdk\Enums\ResponseHeadersFrame;
use Redberry\LaravelCloudSdk\Enums\ResponseHeadersRobotsTag;
use Redberry\LaravelCloudSdk\Enums\SourceControlProvider;
use Redberry\LaravelCloudSdk\Enums\WebsocketMaxConnections;
use Redberry\LaravelCloudSdk\Enums\WebsocketServerType;
use Redberry\LaravelCloudSdk\LaravelCloud as LaravelCloudClient;

/**
 * Applications
 *
 * @method static Collection applications()
 * @method static ApplicationData application(string $id)
 * @method static ApplicationData createApplication(string $repository, string $name, string|CloudRegion $region, string|SourceControlProvider $sourceControlProviderType, string|null $clusterId = null)
 * @method static ApplicationData createApplicationWith(CreateApplicationData $data)
 * @method static ApplicationData updateApplication(string $id, string|SourceControlProvider $sourceControlProviderType = null, string $name = null, string $slug = null, string $defaultEnvironmentId = null, string $repository = null, string|null $slackChannel = null)
 * @method static ApplicationData updateApplicationWith(string $id, UpdateApplicationData $data)
 * @method static void deleteApplication(string $id)
 *
 * Environments
 * @method static Collection environments(string $applicationId)
 * @method static EnvironmentData environment(string $id)
 * @method static EnvironmentData createEnvironment(string $applicationId, string $branch, string $name, string|null $clusterId = null)
 * @method static EnvironmentData createEnvironmentWith(string $applicationId, CreateEnvironmentData $data)
 * @method static EnvironmentData updateEnvironment(string $id, string $name = null, string $slug = null, string|EnvironmentColor $color = null, string $branch = null, string|PhpVersion $phpVersion = null, string|NodeVersion $nodeVersion = null, string|null $buildCommand = null, string|null $deployCommand = null, bool $usesPushToDeploy = null, bool $usesDeployHook = null, bool $usesOctane = null, bool $usesVanityDomain = null, int $timeout = null, int $sleepTimeout = null, int $shutdownTimeout = null, bool $usesPurgeEdgeCacheOnDeploy = null, string|null $nightwatchToken = null, string|CacheStrategy $cacheStrategy = null, string|ResponseHeadersFrame $responseHeadersFrame = null, string|ResponseHeadersContentType $responseHeadersContentType = null, string|ResponseHeadersRobotsTag $responseHeadersRobotsTag = null, HstsData|null $responseHeadersHsts = null, array|null $filesystemKeys = null, string|FirewallRateLimitLevel|null $firewallRateLimitLevel = null, bool $firewallUnderAttackMode = null, string|null $databaseSchemaId = null, string|null $cacheId = null, string|null $websocketApplicationId = null)
 * @method static EnvironmentData updateEnvironmentWith(string $id, UpdateEnvironmentData $data)
 * @method static void deleteEnvironment(string $id)
 *
 * Instances
 * @method static Collection instances(string $environmentId)
 * @method static InstanceData instance(string $id)
 * @method static InstanceData createInstance(string $environmentId, string $name, string|InstanceType $type, string|InstanceSize $size, string|InstanceScalingType $scalingType, int $maxReplicas, int $minReplicas, bool $usesScheduler = null, int|null $scalingCpuThresholdPercentage = null, int|null $scalingMemoryThresholdPercentage = null, array $backgroundProcesses = null)
 * @method static InstanceData createInstanceWith(string $environmentId, CreateInstanceData $data)
 * @method static InstanceData updateInstance(string $id, string $name = null, string|InstanceSize $size = null, string|InstanceScalingType $scalingType = null, int $maxReplicas = null, int $minReplicas = null, bool $usesSleepMode = null, int $sleepTimeout = null, bool $usesScheduler = null, bool $usesOctane = null, bool $usesInertiaSsr = null, int|null $scalingCpuThresholdPercentage = null, int|null $scalingMemoryThresholdPercentage = null)
 * @method static InstanceData updateInstanceWith(string $id, UpdateInstanceData $data)
 * @method static Collection instanceSizes()
 * @method static void deleteInstance(string $id)
 *
 * Domains
 * @method static Collection domains(string $environmentId)
 * @method static DomainData domain(string $id)
 * @method static DomainData createDomain(string $environmentId, string $name, string|DomainRedirect $wwwRedirect, string|DomainVerificationMethod $verificationMethod, string|DomainCloudflareStrategy $cloudflareStrategy = null, bool|null $wildcardEnabled = null, bool|null $allowDowntime = null)
 * @method static DomainData createDomainWith(string $environmentId, CreateDomainData $data)
 * @method static DomainData updateDomain(string $id, string|DomainVerificationMethod $verificationMethod)
 * @method static DomainData updateDomainWith(string $id, UpdateDomainData $data)
 * @method static DomainData verifyDomain(string $id)
 * @method static void deleteDomain(string $id)
 *
 * Database Clusters
 * @method static Collection databaseClusters()
 * @method static DatabaseClusterData databaseCluster(string $id)
 * @method static DatabaseClusterData createDatabaseCluster(string $name, string|DatabaseType $type, string|CloudRegion $region, NeonConfigData|LaravelMysqlConfigData|AwsRdsConfigData $config, int $clusterId = null)
 * @method static DatabaseClusterData createDatabaseClusterWith(CreateDatabaseClusterData $data)
 * @method static DatabaseClusterData updateDatabaseCluster(string $id, NeonConfigData|LaravelMysqlConfigData|AwsRdsConfigData $config)
 * @method static DatabaseClusterData updateDatabaseClusterWith(string $id, UpdateDatabaseClusterData $data)
 * @method static Collection databaseTypes()
 * @method static void deleteDatabaseCluster(string $id)
 *
 * Databases
 * @method static Collection databases(string $clusterId)
 * @method static DatabaseData database(string $clusterId, string $databaseId)
 * @method static DatabaseData createDatabase(string $clusterId, string $name)
 * @method static DatabaseData createDatabaseWith(string $clusterId, CreateDatabaseData $data)
 *
 * Caches
 * @method static Collection caches()
 * @method static CacheData cache(string $id)
 * @method static CacheData createCache(string|CacheType $type, string $name, string|CloudRegion $region, string|CacheSize $size, bool $autoUpgradeEnabled, bool $isPublic, string|EvictionPolicy|null $evictionPolicy = null)
 * @method static CacheData createCacheWith(CreateCacheData $data)
 * @method static CacheData updateCache(string $id, string $name = null, string|CacheSize $size = null, bool $autoUpgradeEnabled = null, bool $isPublic = null, string|EvictionPolicy|null $evictionPolicy = null)
 * @method static CacheData updateCacheWith(string $id, UpdateCacheData $data)
 * @method static Collection cacheTypes()
 * @method static void deleteCache(string $id)
 *
 * Object Storage Buckets
 * @method static Collection buckets()
 * @method static BucketData bucket(string $id)
 * @method static BucketData createBucket(string $name, string|BucketVisibility $visibility, string|BucketJurisdiction $jurisdiction, string $keyName, string|KeyPermission $keyPermission, array|null $allowedOrigins = null)
 * @method static BucketData createBucketWith(CreateBucketData $data)
 * @method static BucketData updateBucket(string $id, string $name = null, string|BucketVisibility $visibility = null, array|null $allowedOrigins = null)
 * @method static BucketData updateBucketWith(string $id, UpdateBucketData $data)
 *
 * Bucket Keys
 * @method static Collection bucketKeys(string $bucketId)
 * @method static BucketKeyData bucketKey(string $keyId)
 * @method static BucketKeyData createBucketKey(string $bucketId, string $name, string|KeyPermission $permission)
 * @method static BucketKeyData createBucketKeyWith(string $bucketId, CreateBucketKeyData $data)
 * @method static BucketKeyData updateBucketKey(string $keyId, string $name)
 * @method static BucketKeyData updateBucketKeyWith(string $keyId, UpdateBucketKeyData $data)
 *
 * Websocket Clusters
 * @method static Collection websocketClusters()
 * @method static WebsocketClusterData websocketCluster(string $id)
 * @method static WebsocketClusterData createWebsocketCluster(string $name, string|WebsocketServerType $type, string|CloudRegion $region, string|WebsocketMaxConnections $maxConnections)
 * @method static WebsocketClusterData createWebsocketClusterWith(CreateWebsocketClusterData $data)
 * @method static WebsocketClusterData updateWebsocketCluster(string $id, string $name = null, string|WebsocketMaxConnections $maxConnections = null)
 * @method static WebsocketClusterData updateWebsocketClusterWith(string $id, UpdateWebsocketClusterData $data)
 * @method static void deleteWebsocketCluster(string $id)
 *
 * Websocket Applications
 * @method static Collection websocketApplications(string $clusterId)
 * @method static WebsocketApplicationData websocketApplication(string $id)
 * @method static WebsocketApplicationData createWebsocketApplication(string $clusterId, string $name, int $pingInterval = null, int $activityTimeout = null, array|null $allowedOrigins = null)
 * @method static WebsocketApplicationData createWebsocketApplicationWith(string $clusterId, CreateWebsocketApplicationData $data)
 * @method static WebsocketApplicationData updateWebsocketApplication(string $id, string $name = null, int $pingInterval = null, int $activityTimeout = null, array|null $allowedOrigins = null)
 * @method static WebsocketApplicationData updateWebsocketApplicationWith(string $id, UpdateWebsocketApplicationData $data)
 * @method static void deleteWebsocketApplication(string $id)
 *
 * Regions
 * @method static Collection regions()
 *
 * @see LaravelCloudClient
 */
class LaravelCloud extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return LaravelCloudClient::class;
    }

    public static function forToken(string $token): LaravelCloudClient
    {
        return new LaravelCloudClient($token);
    }
}
