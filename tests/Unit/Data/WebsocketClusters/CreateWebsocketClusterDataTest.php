<?php

use Redberry\LaravelCloudSdk\Data\WebsocketClusters\CreateWebsocketClusterData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\WebsocketMaxConnections;
use Redberry\LaravelCloudSdk\Enums\WebsocketServerType;

it('can be constructed with required parameters', function () {
    $data = new CreateWebsocketClusterData(
        name: 'test-cluster',
        type: WebsocketServerType::Reverb,
        region: CloudRegion::UsEast1,
        maxConnections: WebsocketMaxConnections::Connections100,
    );

    expect($data->name)->toBe('test-cluster');
    expect($data->type)->toBe(WebsocketServerType::Reverb);
    expect($data->region)->toBe(CloudRegion::UsEast1);
    expect($data->maxConnections)->toBe(WebsocketMaxConnections::Connections100);
});

it('serializes max_connections as snake_case', function () {
    $data = new CreateWebsocketClusterData(
        name: 'test-cluster',
        type: WebsocketServerType::Reverb,
        region: CloudRegion::EuCentral1,
        maxConnections: WebsocketMaxConnections::Connections500,
    );

    $array = $data->toArray();

    expect($array)->toHaveKey('max_connections');
    expect($array)->not->toHaveKey('maxConnections');
    expect($array['max_connections'])->toBe(500);
});
