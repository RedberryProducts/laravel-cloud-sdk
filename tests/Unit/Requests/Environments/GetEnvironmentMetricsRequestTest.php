<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentMetricsData;
use Redberry\LaravelCloudSdk\Enums\MetricPeriod;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\GetEnvironmentMetricsRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new GetEnvironmentMetricsRequest('env-123');

    expect($request->resolveEndpoint())->toBe('/environments/env-123/metrics');
});

it('has the correct HTTP method', function () {
    $request = new GetEnvironmentMetricsRequest('env-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('includes period in query when provided', function () {
    $request = new GetEnvironmentMetricsRequest('env-123', MetricPeriod::ThirtyDays);

    expect($request->query()->all())->toBe(['period' => '30d']);
});

it('omits period from query when null', function () {
    $request = new GetEnvironmentMetricsRequest('env-123');

    expect($request->query()->all())->toBe([]);
});

it('gets environment metrics and returns EnvironmentMetricsData', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/metrics-list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApp = $connector->send(new ListApplicationsRequest)->dtoOrFail()[0];

    Saloon::fake([
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/metrics-list'),
    ]);

    $firstEnv = $connector->send(new ListEnvironmentsRequest($firstApp->id))->dtoOrFail()[0];

    Saloon::fake([
        GetEnvironmentMetricsRequest::class => new LaravelCloudFixture('environments/metrics'),
    ]);

    $response = $connector->send(new GetEnvironmentMetricsRequest($firstEnv->id));

    Saloon::assertSent(GetEnvironmentMetricsRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(EnvironmentMetricsData::class);
});
