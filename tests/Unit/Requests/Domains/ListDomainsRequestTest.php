<?php

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Domains\DomainData;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\Domains\ListDomainsRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new ListDomainsRequest('env-123');

    expect($request->resolveEndpoint())->toBe('/environments/env-123/domains');
});

it('has the correct HTTP method', function () {
    $request = new ListDomainsRequest('env-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('lists domains and returns DomainData collection', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()->first();

    Saloon::fake([
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/list'),
    ]);

    $firstEnvironment = $connector->send(new ListEnvironmentsRequest($firstApplication->id))->dtoOrFail()->first();

    Saloon::fake([
        ListDomainsRequest::class => new LaravelCloudFixture('domains/list'),
    ]);

    $response = $connector->send(new ListDomainsRequest($firstEnvironment->id));

    Saloon::assertSent(ListDomainsRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(Collection::class);
    expect($dto->first())->toBeInstanceOf(DomainData::class);
});
