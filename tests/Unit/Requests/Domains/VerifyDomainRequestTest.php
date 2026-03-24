<?php

use Redberry\LaravelCloudSdk\Data\Domains\DomainData;
use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\Domains\ListDomainsRequest;
use Redberry\LaravelCloudSdk\Requests\Domains\VerifyDomainRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $request = new VerifyDomainRequest('domain-123');

    expect($request->resolveEndpoint())->toBe('/domains/domain-123/verify');
});

it('has the correct HTTP method', function () {
    $request = new VerifyDomainRequest('domain-123');

    expect($request->getMethod())->toBe(Method::POST);
});

it('verifies a domain and returns DomainData', function () {
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

    $firstDomain = $connector->send(new ListDomainsRequest($firstEnvironment->id))->dtoOrFail()->last();

    Saloon::fake([
        VerifyDomainRequest::class => new LaravelCloudFixture('domains/verify'),
    ]);

    $response = $connector->send(new VerifyDomainRequest($firstDomain->id));

    Saloon::assertSent(VerifyDomainRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(DomainData::class);
});
