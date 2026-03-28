<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Domains\DomainData;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\Domains\GetDomainRequest;
use Redberry\LaravelCloudSdk\Requests\Domains\ListDomainsRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new GetDomainRequest('domain-123');

    expect($request->resolveEndpoint())->toBe('/domains/domain-123');
});

it('has the correct HTTP method', function () {
    $request = new GetDomainRequest('domain-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('gets a domain and returns DomainData', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/list'),
        ListDomainsRequest::class => new LaravelCloudFixture('domains/list'),
        GetDomainRequest::class => new LaravelCloudFixture('domains/get'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()->first();
    $firstEnvironment = $connector->send(new ListEnvironmentsRequest($firstApplication->id))->dtoOrFail()->first();
    $firstDomain = $connector->send(new ListDomainsRequest($firstEnvironment->id))->dtoOrFail()->first();

    $response = $connector->send(new GetDomainRequest($firstDomain->id));

    Saloon::assertSent(GetDomainRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(DomainData::class);
});
