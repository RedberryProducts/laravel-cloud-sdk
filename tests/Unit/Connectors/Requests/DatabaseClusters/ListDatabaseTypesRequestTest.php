<?php

use App\Data\LaravelCloud\DatabaseClusters\DatabaseTypeData;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\DatabaseClusters\ListDatabaseTypesRequest;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $request = new ListDatabaseTypesRequest;

    expect($request->resolveEndpoint())->toBe('/databases/types');
});

it('has the correct HTTP method', function () {
    $request = new ListDatabaseTypesRequest;

    expect($request->getMethod())->toBe(Method::GET);
});

it('lists database types and returns DatabaseTypeData collection', function () {
    Saloon::fake([
        ListDatabaseTypesRequest::class => new LaravelCloudFixture('database-clusters/types'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud.token'));
    $response = $connector->send(new ListDatabaseTypesRequest);

    Saloon::assertSent(ListDatabaseTypesRequest::class);
    expect($response->getPsrRequest()->getUri()->getPath())->toBe('/api/databases/types');

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(Collection::class);
    expect($dto)->not->toBeEmpty();
    expect($dto->first())->toBeInstanceOf(DatabaseTypeData::class);
});
