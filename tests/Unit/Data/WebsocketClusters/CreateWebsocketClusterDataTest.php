<?php

use Redberry\LaravelCloudSdk\Data\WebsocketClusters\CreateWebsocketClusterData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\WebsocketMaxConnections;
use Redberry\LaravelCloudSdk\Enums\WebsocketServerType;

it('can be constructed with required parameters', function () {
    $data = new CreateWebsocketClusterData(
        name: 'test-cluster',
        type: WebsocketServerType::REVERB,
        region: CloudRegion::US_EAST_1,
        maxConnections: WebsocketMaxConnections::CONNECTIONS_100,
    );

    expect($data->name)->toBe('test-cluster');
    expect($data->type)->toBe(WebsocketServerType::REVERB);
    expect($data->region)->toBe(CloudRegion::US_EAST_1);
    expect($data->maxConnections)->toBe(WebsocketMaxConnections::CONNECTIONS_100);
});

it('serializes max_connections as snake_case', function () {
    $data = new CreateWebsocketClusterData(
        name: 'test-cluster',
        type: WebsocketServerType::REVERB,
        region: CloudRegion::EU_CENTRAL_1,
        maxConnections: WebsocketMaxConnections::CONNECTIONS_500,
    );

    $array = $data->toArray();

    expect($array)->toHaveKey('max_connections');
    expect($array)->not->toHaveKey('maxConnections');
    expect($array['max_connections'])->toBe(500);
});
