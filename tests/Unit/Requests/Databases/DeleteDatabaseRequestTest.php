<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Databases\CreateDatabaseData;
use Redberry\LaravelCloudSdk\Requests\Databases\CreateDatabaseRequest;
use Redberry\LaravelCloudSdk\Requests\Databases\DeleteDatabaseRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new DeleteDatabaseRequest('cluster-123', 'db-456');

    expect($request->resolveEndpoint())->toBe('/databases/clusters/cluster-123/databases/db-456');
});

it('has the correct HTTP method', function () {
    $request = new DeleteDatabaseRequest('cluster-123', 'db-456');

    expect($request->getMethod())->toBe(Method::DELETE);
});

it('sends the delete request successfully', function () {
    Saloon::fake([
        CreateDatabaseRequest::class => new LaravelCloudFixture('databases/delete-create'),
        DeleteDatabaseRequest::class => new LaravelCloudFixture('databases/delete'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $database = $connector->send(new CreateDatabaseRequest('red-paper-65989343', new CreateDatabaseData(
        name: 'sdk-delete-test',
    )))->dtoOrFail();

    $response = $connector->send(new DeleteDatabaseRequest('red-paper-65989343', $database->id));

    Saloon::assertSent(DeleteDatabaseRequest::class);
    expect($response->successful())->toBeTrue();
});
