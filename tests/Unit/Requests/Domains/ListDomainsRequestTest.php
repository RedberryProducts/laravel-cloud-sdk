<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Domains\DomainData;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\Domains\ListDomainsRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Saloon\PaginationPlugin\Contracts\Paginatable;

it('resolves the endpoint correctly', function () {
    $request = new ListDomainsRequest('env-123');

    expect($request->resolveEndpoint())->toBe('/environments/env-123/domains');
});

it('has the correct HTTP method', function () {
    $request = new ListDomainsRequest('env-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('implements Paginatable', function () {
    $request = new ListDomainsRequest('env-123');

    expect($request)->toBeInstanceOf(Paginatable::class);
});

it('includes relationships in default query', function () {
    $request = new ListDomainsRequest('env-123');

    expect($request->query()->all())->toBe([
        'include' => 'environment',
    ]);
});

it('lists domains and returns DomainData collection', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()[0];

    Saloon::fake([
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/list'),
    ]);

    $firstEnvironment = $connector->send(new ListEnvironmentsRequest($firstApplication->id))->dtoOrFail()[0];

    Saloon::fake([
        ListDomainsRequest::class => new LaravelCloudFixture('domains/list'),
    ]);

    $response = $connector->send(new ListDomainsRequest($firstEnvironment->id));

    Saloon::assertSent(ListDomainsRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeArray();
    expect($dto[0])->toBeInstanceOf(DomainData::class);
});
