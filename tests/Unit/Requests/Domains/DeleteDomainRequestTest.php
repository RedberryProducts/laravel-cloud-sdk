<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Applications\CreateApplicationData;
use Redberry\LaravelCloudSdk\Data\Domains\CreateDomainData;
use Redberry\LaravelCloudSdk\Data\Environments\CreateEnvironmentData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\DomainRedirect;
use Redberry\LaravelCloudSdk\Enums\DomainVerificationMethod;
use Redberry\LaravelCloudSdk\Enums\SourceControlProvider;
use Redberry\LaravelCloudSdk\Requests\Applications\CreateApplicationRequest;
use Redberry\LaravelCloudSdk\Requests\Applications\DeleteApplicationRequest;
use Redberry\LaravelCloudSdk\Requests\Domains\CreateDomainRequest;
use Redberry\LaravelCloudSdk\Requests\Domains\DeleteDomainRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\CreateEnvironmentRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new DeleteDomainRequest('domain-123');

    expect($request->resolveEndpoint())->toBe('/domains/domain-123');
});

it('has the correct HTTP method', function () {
    $request = new DeleteDomainRequest('domain-123');

    expect($request->getMethod())->toBe(Method::DELETE);
});

it('sends the delete request successfully', function () {
    Saloon::fake([
        CreateApplicationRequest::class => new LaravelCloudFixture('domains/delete-create-app'),
        CreateEnvironmentRequest::class => new LaravelCloudFixture('domains/delete-create-env'),
        CreateDomainRequest::class => new LaravelCloudFixture('domains/delete-create'),
        DeleteDomainRequest::class => new LaravelCloudFixture('domains/delete'),
        DeleteApplicationRequest::class => new LaravelCloudFixture('domains/delete-cleanup-app'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $app = $connector->send(new CreateApplicationRequest(new CreateApplicationData(
        repository: 'RedberryProducts/redberry-automations',
        name: 'sdk-domain-delete-test',
        region: CloudRegion::UsEast1,
        sourceControlProviderType: SourceControlProvider::Github,
    )))->dtoOrFail();

    $env = $connector->send(new CreateEnvironmentRequest($app->id, new CreateEnvironmentData(
        branch: 'main',
        name: 'sdk-delete-test',
    )))->dtoOrFail();

    $domain = $connector->send(new CreateDomainRequest($env->id, new CreateDomainData(
        name: 'sdk-delete-test.redberry.ge',
        wwwRedirect: DomainRedirect::WwwToRoot,
        verificationMethod: DomainVerificationMethod::RealTime,
    )))->dtoOrFail();

    $response = $connector->send(new DeleteDomainRequest($domain->id));

    Saloon::assertSent(DeleteDomainRequest::class);
    expect($response->successful())->toBeTrue();

    $connector->send(new DeleteApplicationRequest($app->id));
});
