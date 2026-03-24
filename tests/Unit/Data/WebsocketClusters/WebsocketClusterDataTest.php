<?php

use App\Data\LaravelCloud\WebsocketClusters\WebsocketClusterData;
use App\Enums\LaravelCloud\CloudRegion;
use App\Enums\LaravelCloud\WebsocketConnectionDistributionStrategy;
use App\Enums\LaravelCloud\WebsocketMaxConnections;
use App\Enums\LaravelCloud\WebsocketServerType;
use App\Enums\LaravelCloud\WebsocketStatus;
use Carbon\CarbonImmutable;

it('can be created from API response data', function () {
    $responseData = [
        'name' => 'my-cluster',
        'type' => 'reverb',
        'region' => 'us-east-1',
        'status' => 'available',
        'max_connections' => 100,
        'connection_distribution_strategy' => 'evenly',
        'hostname' => 'ws.example.com',
        'created_at' => '2024-06-15T10:30:00Z',
    ];

    $data = WebsocketClusterData::fromResponse($responseData, 'ws-123');

    expect($data)->toBeInstanceOf(WebsocketClusterData::class);
    expect($data->id)->toBe('ws-123');
    expect($data->name)->toBe('my-cluster');
    expect($data->type)->toBe(WebsocketServerType::REVERB);
    expect($data->region)->toBe(CloudRegion::US_EAST_1);
    expect($data->status)->toBe(WebsocketStatus::AVAILABLE);
    expect($data->maxConnections)->toBe(WebsocketMaxConnections::CONNECTIONS_100);
    expect($data->connectionDistributionStrategy)->toBe(WebsocketConnectionDistributionStrategy::EVENLY);
    expect($data->hostname)->toBe('ws.example.com');
    expect($data->createdAt)->toBeInstanceOf(CarbonImmutable::class);
});

it('handles null created_at', function () {
    $responseData = [
        'name' => 'my-cluster',
        'type' => 'reverb',
        'region' => 'eu-central-1',
        'status' => 'creating',
        'max_connections' => 500,
        'connection_distribution_strategy' => 'custom',
        'hostname' => 'ws.example.com',
        'created_at' => null,
    ];

    $data = WebsocketClusterData::fromResponse($responseData, 'ws-456');

    expect($data->createdAt)->toBeNull();
    expect($data->status)->toBe(WebsocketStatus::CREATING);
    expect($data->maxConnections)->toBe(WebsocketMaxConnections::CONNECTIONS_500);
    expect($data->connectionDistributionStrategy)->toBe(WebsocketConnectionDistributionStrategy::CUSTOM);
});
