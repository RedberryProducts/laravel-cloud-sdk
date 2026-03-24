<?php

use App\Data\LaravelCloud\Domains\CreateDomainData;
use App\Data\LaravelCloud\Domains\DomainData;
use App\Enums\LaravelCloud\DomainCloudflareStrategy;
use App\Enums\LaravelCloud\DomainRedirect;
use App\Enums\LaravelCloud\DomainVerificationMethod;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\Applications\ListApplicationsRequest;
use App\Http\Integrations\LaravelCloud\Requests\Domains\CreateDomainRequest;
use App\Http\Integrations\LaravelCloud\Requests\Environments\ListEnvironmentsRequest;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $data = new CreateDomainData(
        name: 'example.com',
        wwwRedirect: DomainRedirect::ROOT_TO_WWW,
        verificationMethod: DomainVerificationMethod::PRE_VERIFICATION,
    );
    $request = new CreateDomainRequest('env-123', $data);

    expect($request->resolveEndpoint())->toBe('/environments/env-123/domains');
});

it('has the correct HTTP method', function () {
    $data = new CreateDomainData(
        name: 'example.com',
        wwwRedirect: DomainRedirect::ROOT_TO_WWW,
        verificationMethod: DomainVerificationMethod::PRE_VERIFICATION,
    );
    $request = new CreateDomainRequest('env-123', $data);

    expect($request->getMethod())->toBe(Method::POST);
});

it('implements HasBody', function () {
    $data = new CreateDomainData(
        name: 'example.com',
        wwwRedirect: DomainRedirect::ROOT_TO_WWW,
        verificationMethod: DomainVerificationMethod::PRE_VERIFICATION,
    );
    $request = new CreateDomainRequest('env-123', $data);

    expect($request)->toBeInstanceOf(HasBody::class);
});

it('sends correct body with all optional fields', function () {
    $data = new CreateDomainData(
        name: 'example.com',
        wwwRedirect: DomainRedirect::ROOT_TO_WWW,
        verificationMethod: DomainVerificationMethod::PRE_VERIFICATION,
        cloudflareStrategy: DomainCloudflareStrategy::NONE,
        wildcardEnabled: false,
        allowDowntime: false,
    );
    $request = new CreateDomainRequest('env-123', $data);
    $body = $request->body()->all();

    expect($body['name'])->toBe('example.com');
    expect($body['www_redirect'])->toBe('root_to_www');
    expect($body['verification_method'])->toBe('pre_verification');
    expect($body['cloudflare_strategy'])->toBe('none');
    expect($body['wildcard_enabled'])->toBeFalse();
    expect($body['allow_downtime'])->toBeFalse();
});

it('excludes unset optional fields from body', function () {
    $data = new CreateDomainData(
        name: 'example.com',
        wwwRedirect: DomainRedirect::ROOT_TO_WWW,
        verificationMethod: DomainVerificationMethod::PRE_VERIFICATION,
    );
    $request = new CreateDomainRequest('env-123', $data);
    $body = $request->body()->all();

    expect($body)->toHaveKey('name');
    expect($body)->not->toHaveKey('cloudflare_strategy');
    expect($body)->not->toHaveKey('wildcard_enabled');
    expect($body)->not->toHaveKey('allow_downtime');
});

it('creates a domain and returns DomainData', function () {
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
        CreateDomainRequest::class => new LaravelCloudFixture('domains/create'),
    ]);

    $data = new CreateDomainData(
        name: 'laravel-cloud-sdk.redberry.ge',
        wwwRedirect: DomainRedirect::ROOT_TO_WWW,
        verificationMethod: DomainVerificationMethod::PRE_VERIFICATION,
        cloudflareStrategy: DomainCloudflareStrategy::NONE,
        wildcardEnabled: false,
        allowDowntime: false,
    );
    $response = $connector->send(new CreateDomainRequest($firstEnvironment->id, $data));

    Saloon::assertSent(CreateDomainRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(DomainData::class);
});
