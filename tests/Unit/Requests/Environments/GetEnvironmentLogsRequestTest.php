<?php

use Illuminate\Support\LazyCollection;
use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentLogEntryData;
use Redberry\LaravelCloudSdk\Enums\LogFilterType;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\GetEnvironmentLogsRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Saloon\PaginationPlugin\Contracts\HasRequestPagination;
use Saloon\PaginationPlugin\Contracts\Paginatable;

it('resolves the endpoint correctly', function () {
    $request = new GetEnvironmentLogsRequest('env-123', '2026-04-09T00:00:00Z', '2026-04-09T23:59:59Z');

    expect($request->resolveEndpoint())->toBe('/environments/env-123/logs');
});

it('has the correct HTTP method', function () {
    $request = new GetEnvironmentLogsRequest('env-123', '2026-04-09T00:00:00Z', '2026-04-09T23:59:59Z');

    expect($request->getMethod())->toBe(Method::GET);
});

it('implements Paginatable and HasRequestPagination', function () {
    $request = new GetEnvironmentLogsRequest('env-123', '2026-04-09T00:00:00Z', '2026-04-09T23:59:59Z');

    expect($request)->toBeInstanceOf(Paginatable::class);
    expect($request)->toBeInstanceOf(HasRequestPagination::class);
});

it('includes required from and to query params', function () {
    $request = new GetEnvironmentLogsRequest('env-123', '2026-04-09T00:00:00Z', '2026-04-09T23:59:59Z');

    expect($request->query()->all())->toBe([
        'from' => '2026-04-09T00:00:00Z',
        'to' => '2026-04-09T23:59:59Z',
    ]);
});

it('includes optional query params when provided', function () {
    $request = new GetEnvironmentLogsRequest(
        'env-123',
        '2026-04-09T00:00:00Z',
        '2026-04-09T23:59:59Z',
        searchQuery: 'error',
        type: LogFilterType::Application,
    );

    expect($request->query()->all())->toBe([
        'from' => '2026-04-09T00:00:00Z',
        'to' => '2026-04-09T23:59:59Z',
        'query' => 'error',
        'type' => 'application',
    ]);
});

it('omits optional params when null', function () {
    $request = new GetEnvironmentLogsRequest('env-123', '2026-04-09T00:00:00Z', '2026-04-09T23:59:59Z');

    $query = $request->query()->all();
    expect($query)->not->toHaveKey('query');
    expect($query)->not->toHaveKey('type');
});

it('gets environment logs and returns EnvironmentLogEntryData items', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/logs-list'),
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/logs-list'),
        GetEnvironmentLogsRequest::class => new LaravelCloudFixture('environments/logs'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApp = $connector->send(new ListApplicationsRequest)->dtoOrFail()[0];
    $firstEnv = $connector->send(new ListEnvironmentsRequest($firstApp->id))->dtoOrFail()[0];

    $to = now()->toIso8601String();
    $from = now()->subDay()->toIso8601String();

    $request = new GetEnvironmentLogsRequest($firstEnv->id, $from, $to);
    $response = $connector->send($request);

    Saloon::assertSent(GetEnvironmentLogsRequest::class);

    $items = $response->dtoOrFail();
    expect($items)->toBeArray();

    if (count($items) > 0) {
        expect($items[0])->toBeInstanceOf(EnvironmentLogEntryData::class);
    }
});

it('supports cursor pagination via HasRequestPagination', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/logs-list'),
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/logs-list'),
        GetEnvironmentLogsRequest::class => new LaravelCloudFixture('environments/logs-paginated'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApp = $connector->send(new ListApplicationsRequest)->dtoOrFail()[0];
    $firstEnv = $connector->send(new ListEnvironmentsRequest($firstApp->id))->dtoOrFail()[0];

    $to = now()->toIso8601String();
    $from = now()->subDay()->toIso8601String();

    $request = new GetEnvironmentLogsRequest($firstEnv->id, $from, $to);
    $collection = $request->paginate($connector)->collect();

    expect($collection)->toBeInstanceOf(LazyCollection::class);

    $first = $collection->first();

    if ($first !== null) {
        expect($first)->toBeInstanceOf(EnvironmentLogEntryData::class);
    }
});
