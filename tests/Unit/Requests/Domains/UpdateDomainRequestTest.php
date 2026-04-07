<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Domains\DomainData;
use Redberry\LaravelCloudSdk\Data\Domains\UpdateDomainData;
use Redberry\LaravelCloudSdk\Enums\DomainVerificationMethod;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\Domains\ListDomainsRequest;
use Redberry\LaravelCloudSdk\Requests\Domains\UpdateDomainRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $data = new UpdateDomainData(verificationMethod: DomainVerificationMethod::REAL_TIME);
    $request = new UpdateDomainRequest('domain-123', $data);

    expect($request->resolveEndpoint())->toBe('/domains/domain-123');
});

it('has the correct HTTP method', function () {
    $data = new UpdateDomainData(verificationMethod: DomainVerificationMethod::REAL_TIME);
    $request = new UpdateDomainRequest('domain-123', $data);

    expect($request->getMethod())->toBe(Method::PATCH);
});

it('sends correct body', function () {
    $data = new UpdateDomainData(verificationMethod: DomainVerificationMethod::REAL_TIME);
    $request = new UpdateDomainRequest('domain-123', $data);
    $body = $request->body()->all();

    expect($body['verification_method'])->toBe('real_time');
});

it('updates a domain and returns DomainData', function () {
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

    $firstDomain = $connector->send(new ListDomainsRequest($firstEnvironment->id))->dtoOrFail()[0];

    Saloon::fake([
        UpdateDomainRequest::class => new LaravelCloudFixture('domains/update'),
    ]);

    $data = new UpdateDomainData(verificationMethod: DomainVerificationMethod::REAL_TIME);
    $response = $connector->send(new UpdateDomainRequest($firstDomain->id, $data));

    Saloon::assertSent(UpdateDomainRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(DomainData::class);
});
