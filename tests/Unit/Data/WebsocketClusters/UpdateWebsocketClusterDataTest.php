<?php

use Redberry\LaravelCloudSdk\Data\WebsocketClusters\UpdateWebsocketClusterData;
use Redberry\LaravelCloudSdk\Enums\WebsocketMaxConnections;
use Spatie\LaravelData\Optional;

it('can be constructed with partial parameters', function () {
    $data = new UpdateWebsocketClusterData(name: 'updated-cluster');

    expect($data->name)->toBe('updated-cluster');
    expect($data->maxConnections)->toBeInstanceOf(Optional::class);
});

it('can be constructed with all parameters', function () {
    $data = new UpdateWebsocketClusterData(
        name: 'updated-cluster',
        maxConnections: WebsocketMaxConnections::CONNECTIONS_200,
    );

    expect($data->name)->toBe('updated-cluster');
    expect($data->maxConnections)->toBe(WebsocketMaxConnections::CONNECTIONS_200);
});

it('defaults all parameters to Optional', function () {
    $data = new UpdateWebsocketClusterData;

    expect($data->name)->toBeInstanceOf(Optional::class);
    expect($data->maxConnections)->toBeInstanceOf(Optional::class);
});

it('serializes max_connections as snake_case', function () {
    $data = new UpdateWebsocketClusterData(
        maxConnections: WebsocketMaxConnections::CONNECTIONS_2000,
    );

    $array = $data->toArray();

    expect($array)->toHaveKey('max_connections');
    expect($array)->not->toHaveKey('maxConnections');
    expect($array['max_connections'])->toBe(2000);
});
