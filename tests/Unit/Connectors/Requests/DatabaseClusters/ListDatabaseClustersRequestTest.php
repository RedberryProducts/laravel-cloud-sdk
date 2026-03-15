<?php

use App\Data\LaravelCloud\DatabaseClusters\DatabaseClusterData;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\DatabaseClusters\ListDatabaseClustersRequest;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $request = new ListDatabaseClustersRequest;

    expect($request->resolveEndpoint())->toBe('/databases/clusters');
});

it('has the correct HTTP method', function () {
    $request = new ListDatabaseClustersRequest;

    expect($request->getMethod())->toBe(Method::GET);
});

it('lists database clusters and returns DatabaseClusterData collection', function () {
    Saloon::fake([
        ListDatabaseClustersRequest::class => new LaravelCloudFixture('database-clusters/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud.token'));
    $response = $connector->send(new ListDatabaseClustersRequest);

    Saloon::assertSent(ListDatabaseClustersRequest::class);
    expect($response->getPsrRequest()->getMethod())->toBe('GET');
    expect($response->getPsrRequest()->getUri()->getPath())->toBe('/api/databases/clusters');

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(Collection::class);
    expect($dto->first())->toBeInstanceOf(DatabaseClusterData::class);
});
