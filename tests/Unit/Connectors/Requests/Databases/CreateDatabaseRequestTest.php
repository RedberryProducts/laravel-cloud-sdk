<?php

use App\Data\LaravelCloud\Databases\CreateDatabaseData;
use App\Data\LaravelCloud\Databases\DatabaseData;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\DatabaseClusters\ListDatabaseClustersRequest;
use App\Http\Integrations\LaravelCloud\Requests\Databases\CreateDatabaseRequest;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $data = new CreateDatabaseData(name: 'test-db');
    $request = new CreateDatabaseRequest('cluster-123', $data);

    expect($request->resolveEndpoint())->toBe('/databases/clusters/cluster-123/databases');
});

it('has the correct HTTP method', function () {
    $data = new CreateDatabaseData(name: 'test-db');
    $request = new CreateDatabaseRequest('cluster-123', $data);

    expect($request->getMethod())->toBe(Method::POST);
});

it('implements HasBody', function () {
    $data = new CreateDatabaseData(name: 'test-db');
    $request = new CreateDatabaseRequest('cluster-123', $data);

    expect($request)->toBeInstanceOf(HasBody::class);
});

it('sends correct body', function () {
    $data = new CreateDatabaseData(name: 'test-db');
    $request = new CreateDatabaseRequest('cluster-123', $data);
    $body = $request->body()->all();

    expect($body['name'])->toBe('test-db');
});

it('creates a database and returns DatabaseData with all fields', function () {
    Saloon::fake([
        ListDatabaseClustersRequest::class => new LaravelCloudFixture('database-clusters/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud.token'));
    $firstCluster = $connector->send(new ListDatabaseClustersRequest)->dtoOrFail()->first();

    Saloon::fake([
        CreateDatabaseRequest::class => new LaravelCloudFixture('databases/create'),
    ]);

    $data = new CreateDatabaseData(name: 'test-db');
    $response = $connector->send(new CreateDatabaseRequest($firstCluster->id, $data));

    Saloon::assertSent(CreateDatabaseRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(DatabaseData::class);
    expect($dto->id)->toBe('47343217');
    expect($dto->name)->toBe('test-db');
    expect($dto->createdAt)->not->toBeNull();
});
