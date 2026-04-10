<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\ClusterStatus;
use Redberry\LaravelCloudSdk\Enums\ClusterType;
use Redberry\LaravelCloudSdk\Requests\DedicatedClusters\ListDedicatedClustersRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Saloon\PaginationPlugin\Contracts\Paginatable;

it('resolves the endpoint correctly', function () {
    $request = new ListDedicatedClustersRequest;

    expect($request->resolveEndpoint())->toBe('/dedicated-clusters');
});

it('has the correct HTTP method', function () {
    $request = new ListDedicatedClustersRequest;

    expect($request->getMethod())->toBe(Method::GET);
});

it('implements Paginatable', function () {
    $request = new ListDedicatedClustersRequest;

    expect($request)->toBeInstanceOf(Paginatable::class);
});

it('builds query filters from enum values', function () {
    $request = new ListDedicatedClustersRequest(
        region: CloudRegion::UsEast1,
        type: ClusterType::Applications,
        status: ClusterStatus::Active,
    );

    expect($request->defaultQuery())->toBe([
        'filter[region]' => 'us-east-1',
        'filter[type]' => 'applications',
        'filter[status]' => 'active',
    ]);
});

it('builds query filters from string values', function () {
    $request = new ListDedicatedClustersRequest(
        region: 'eu-central-1',
        type: 'mysql-databases',
        status: 'draft',
    );

    expect($request->defaultQuery())->toBe([
        'filter[region]' => 'eu-central-1',
        'filter[type]' => 'mysql-databases',
        'filter[status]' => 'draft',
    ]);
});

it('omits null filters from query', function () {
    $request = new ListDedicatedClustersRequest(
        region: CloudRegion::UsEast1,
    );

    expect($request->defaultQuery())->toBe([
        'filter[region]' => 'us-east-1',
    ]);
});

it('returns empty query when no filters', function () {
    $request = new ListDedicatedClustersRequest;

    expect($request->defaultQuery())->toBe([]);
});

it('lists dedicated clusters via connector', function () {
    Saloon::fake([
        ListDedicatedClustersRequest::class => new LaravelCloudFixture('dedicated-clusters/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $response = $connector->send(new ListDedicatedClustersRequest);

    Saloon::assertSent(ListDedicatedClustersRequest::class);
    expect($response->getPsrRequest()->getMethod())->toBe('GET');
    expect($response->getPsrRequest()->getUri()->getPath())->toBe('/api/dedicated-clusters');
});
