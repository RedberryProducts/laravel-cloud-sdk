<?php

use App\Data\LaravelCloud\Domains\DomainData;
use App\Data\LaravelCloud\Domains\UpdateDomainData;
use App\Enums\LaravelCloud\DomainVerificationMethod;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\Applications\ListApplicationsRequest;
use App\Http\Integrations\LaravelCloud\Requests\Domains\ListDomainsRequest;
use App\Http\Integrations\LaravelCloud\Requests\Domains\UpdateDomainRequest;
use App\Http\Integrations\LaravelCloud\Requests\Environments\ListEnvironmentsRequest;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

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
        UpdateDomainRequest::class => new LaravelCloudFixture('domains/update'),
    ]);

    $data = new UpdateDomainData(verificationMethod: DomainVerificationMethod::REAL_TIME);
    $response = $connector->send(new UpdateDomainRequest($firstDomain->id, $data));

    Saloon::assertSent(UpdateDomainRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(DomainData::class);
});
