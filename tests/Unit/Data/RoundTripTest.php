<?php

use Redberry\LaravelCloudSdk\Data\Buckets\CreateBucketData;
use Redberry\LaravelCloudSdk\Data\Caches\CreateCacheData;
use Redberry\LaravelCloudSdk\Data\Instances\CreateInstanceData;
use Redberry\LaravelCloudSdk\Data\WebsocketClusters\CreateWebsocketClusterData;
use Redberry\LaravelCloudSdk\Enums\BucketJurisdiction;
use Redberry\LaravelCloudSdk\Enums\BucketVisibility;
use Redberry\LaravelCloudSdk\Enums\CacheSize;
use Redberry\LaravelCloudSdk\Enums\CacheType;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\EvictionPolicy;
use Redberry\LaravelCloudSdk\Enums\InstanceScalingType;
use Redberry\LaravelCloudSdk\Enums\InstanceSize;
use Redberry\LaravelCloudSdk\Enums\InstanceType;
use Redberry\LaravelCloudSdk\Enums\KeyPermission;
use Redberry\LaravelCloudSdk\Enums\WebsocketMaxConnections;
use Redberry\LaravelCloudSdk\Enums\WebsocketServerType;

it('round-trips CreateBucketData through toArray + from', function () {
    $original = new CreateBucketData(
        name: 'media',
        visibility: BucketVisibility::Public,
        jurisdiction: BucketJurisdiction::Default,
        keyName: 'media-key',
        keyPermission: KeyPermission::ReadWrite,
        allowedOrigins: ['https://example.com'],
    );

    $reconstructed = CreateBucketData::from($original->toArray());

    expect($reconstructed->toArray())->toBe($original->toArray());
});

it('round-trips CreateInstanceData through toArray + from', function () {
    $original = new CreateInstanceData(
        name: 'app-compute',
        type: InstanceType::App,
        size: InstanceSize::FlexC2vcpu512mb,
        scalingType: InstanceScalingType::Auto,
        maxReplicas: 4,
        minReplicas: 2,
        usesScheduler: true,
        scalingCpuThresholdPercentage: 70,
        scalingMemoryThresholdPercentage: 80,
    );

    $reconstructed = CreateInstanceData::from($original->toArray());

    expect($reconstructed->name)->toBe('app-compute')
        ->and($reconstructed->type)->toBe(InstanceType::App)
        ->and($reconstructed->size)->toBe(InstanceSize::FlexC2vcpu512mb)
        ->and($reconstructed->scalingType)->toBe(InstanceScalingType::Auto)
        ->and($reconstructed->maxReplicas)->toBe(4)
        ->and($reconstructed->minReplicas)->toBe(2)
        ->and($reconstructed->usesScheduler)->toBeTrue()
        ->and($reconstructed->scalingCpuThresholdPercentage)->toBe(70)
        ->and($reconstructed->scalingMemoryThresholdPercentage)->toBe(80);
});

it('round-trips CreateCacheData through toArray + from', function () {
    $original = new CreateCacheData(
        type: CacheType::UpstashRedis,
        name: 'primary-cache',
        region: CloudRegion::UsEast1,
        size: CacheSize::Upstash1Gb,
        autoUpgradeEnabled: true,
        isPublic: false,
        evictionPolicy: EvictionPolicy::AllKeysLru,
    );

    $reconstructed = CreateCacheData::from($original->toArray());

    expect($reconstructed->toArray())->toBe($original->toArray());
});

it('round-trips CreateWebsocketClusterData through toArray + from', function () {
    $original = new CreateWebsocketClusterData(
        name: 'primary-ws',
        type: WebsocketServerType::Reverb,
        region: CloudRegion::UsEast1,
        maxConnections: WebsocketMaxConnections::Connections500,
    );

    $reconstructed = CreateWebsocketClusterData::from($original->toArray());

    expect($reconstructed->toArray())->toBe($original->toArray());
});
