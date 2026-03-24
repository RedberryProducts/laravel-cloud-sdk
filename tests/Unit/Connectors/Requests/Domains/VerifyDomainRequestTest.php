<?php

use App\Data\LaravelCloud\Domains\DomainData;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\Applications\ListApplicationsRequest;
use App\Http\Integrations\LaravelCloud\Requests\Domains\ListDomainsRequest;
use App\Http\Integrations\LaravelCloud\Requests\Domains\VerifyDomainRequest;
use App\Http\Integrations\LaravelCloud\Requests\Environments\ListEnvironmentsRequest;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

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

    $connector = new LaravelCloudConnector(config('laravel-cloud.token'));
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
