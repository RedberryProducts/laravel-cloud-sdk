<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Domains\CreateDomainData;
use Redberry\LaravelCloudSdk\Data\Domains\DomainData;
use Redberry\LaravelCloudSdk\Enums\DomainCloudflareStrategy;
use Redberry\LaravelCloudSdk\Enums\DomainRedirect;
use Redberry\LaravelCloudSdk\Enums\DomainVerificationMethod;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\Domains\CreateDomainRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $data = new CreateDomainData(
        name: 'example.com',
        wwwRedirect: DomainRedirect::RootToWww,
        verificationMethod: DomainVerificationMethod::PreVerification,
    );
    $request = new CreateDomainRequest('env-123', $data);

    expect($request->resolveEndpoint())->toBe('/environments/env-123/domains');
});

it('has the correct HTTP method', function () {
    $data = new CreateDomainData(
        name: 'example.com',
        wwwRedirect: DomainRedirect::RootToWww,
        verificationMethod: DomainVerificationMethod::PreVerification,
    );
    $request = new CreateDomainRequest('env-123', $data);

    expect($request->getMethod())->toBe(Method::POST);
});

it('implements HasBody', function () {
    $data = new CreateDomainData(
        name: 'example.com',
        wwwRedirect: DomainRedirect::RootToWww,
        verificationMethod: DomainVerificationMethod::PreVerification,
    );
    $request = new CreateDomainRequest('env-123', $data);

    expect($request)->toBeInstanceOf(HasBody::class);
});

it('sends correct body with all optional fields', function () {
    $data = new CreateDomainData(
        name: 'example.com',
        wwwRedirect: DomainRedirect::RootToWww,
        verificationMethod: DomainVerificationMethod::PreVerification,
        cloudflareStrategy: DomainCloudflareStrategy::None,
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
        wwwRedirect: DomainRedirect::RootToWww,
        verificationMethod: DomainVerificationMethod::PreVerification,
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

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()[0];

    Saloon::fake([
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/list'),
    ]);

    $firstEnvironment = $connector->send(new ListEnvironmentsRequest($firstApplication->id))->dtoOrFail()[0];

    Saloon::fake([
        CreateDomainRequest::class => new LaravelCloudFixture('domains/create'),
    ]);

    $data = new CreateDomainData(
        name: 'laravel-cloud-sdk.redberry.ge',
        wwwRedirect: DomainRedirect::RootToWww,
        verificationMethod: DomainVerificationMethod::PreVerification,
        cloudflareStrategy: DomainCloudflareStrategy::None,
        wildcardEnabled: false,
        allowDowntime: false,
    );
    $response = $connector->send(new CreateDomainRequest($firstEnvironment->id, $data));

    Saloon::assertSent(CreateDomainRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(DomainData::class);
});
