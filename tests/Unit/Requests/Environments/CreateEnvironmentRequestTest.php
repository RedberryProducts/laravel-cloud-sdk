<?php

use Redberry\LaravelCloudSdk\Data\Environments\CreateEnvironmentData;
use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentData;
use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentVariableData;
use Redberry\LaravelCloudSdk\Data\Environments\HstsData;
use Redberry\LaravelCloudSdk\Data\Environments\NetworkSettingsData;
use Redberry\LaravelCloudSdk\Enums\EnvironmentStatus;
use Redberry\LaravelCloudSdk\Enums\NodeVersion;
use Redberry\LaravelCloudSdk\Enums\PhpVersion;
use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\CreateEnvironmentRequest;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $data = new CreateEnvironmentData(branch: 'main', name: 'staging');
    $request = new CreateEnvironmentRequest('app-123', $data);

    expect($request->resolveEndpoint())->toBe('/applications/app-123/environments');
});

it('has the correct HTTP method', function () {
    $data = new CreateEnvironmentData(branch: 'main', name: 'staging');
    $request = new CreateEnvironmentRequest('app-123', $data);

    expect($request->getMethod())->toBe(Method::POST);
});

it('implements HasBody', function () {
    $data = new CreateEnvironmentData(branch: 'main', name: 'staging');
    $request = new CreateEnvironmentRequest('app-123', $data);

    expect($request)->toBeInstanceOf(HasBody::class);
});

it('sends correct body', function () {
    $data = new CreateEnvironmentData(branch: 'main', name: 'staging');
    $request = new CreateEnvironmentRequest('app-123', $data);
    $body = $request->body()->all();

    expect($body['branch'])->toBe('main');
    expect($body['name'])->toBe('staging');
    expect($body)->not->toHaveKey('cluster_id');
});

it('sends optional cluster_id in body when provided', function () {
    $data = new CreateEnvironmentData(branch: 'main', name: 'staging', clusterId: 'cluster-123');
    $request = new CreateEnvironmentRequest('app-123', $data);
    $body = $request->body()->all();

    expect($body['cluster_id'])->toBe('cluster-123');
});

it('creates an environment and returns EnvironmentData with all fields', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()->first();

    Saloon::fake([
        CreateEnvironmentRequest::class => new LaravelCloudFixture('environments/create'),
    ]);

    $data = new CreateEnvironmentData(branch: 'main', name: 'staging');
    $response = $connector->send(new CreateEnvironmentRequest($firstApplication->id, $data));

    Saloon::assertSent(CreateEnvironmentRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(EnvironmentData::class);
    expect($dto->id)->toBe('env-a158d785-0e99-4337-ab52-5e2153848d63');
    expect($dto->name)->toBe('staging');
    expect($dto->slug)->toBe('staging-4');
    expect($dto->status)->toBe(EnvironmentStatus::RUNNING);
    expect($dto->phpMajorVersion)->toBe(PhpVersion::V8_4);
    expect($dto->nodeVersion)->toBe(NodeVersion::V24);
    expect($dto->vanityDomain)->toBe('updated-app-staging-hgo3vh.laravel.cloud');
    expect($dto->createdFromAutomation)->toBeFalse();
    expect($dto->usesOctane)->toBeFalse();
    expect($dto->usesHibernation)->toBeFalse();
    expect($dto->usesPushToDeploy)->toBeTrue();
    expect($dto->usesDeployHook)->toBeFalse();
    expect($dto->buildCommand)->toBeString();
    expect($dto->deployCommand)->toBeString();
    expect($dto->environmentVariables)->toBeArray();
    expect($dto->environmentVariables[0])->toBeInstanceOf(EnvironmentVariableData::class);
    expect($dto->environmentVariables[0]->key)->toBe('APP_KEY');
    expect($dto->networkSettings)->toBeInstanceOf(NetworkSettingsData::class);
    expect($dto->networkSettings->cacheStrategy)->toBe('default');
    expect($dto->networkSettings->responseHeadersFrame)->toBe('deny');
    expect($dto->networkSettings->responseHeadersContentType)->toBe('nosniff');
    expect($dto->networkSettings->responseHeadersRobotsTag)->toBe('index, follow');
    expect($dto->networkSettings->responseHeadersHsts)->toBeInstanceOf(HstsData::class);
    expect($dto->networkSettings->responseHeadersHsts->maxAge)->toBeNull();
    expect($dto->networkSettings->responseHeadersHsts->includeSubdomains)->toBeFalse();
    expect($dto->networkSettings->responseHeadersHsts->preload)->toBeFalse();
    expect($dto->networkSettings->firewallRateLimitLevel)->toBeNull();
    expect($dto->networkSettings->firewallUnderAttackMode)->toBeFalse();
    expect($dto->createdAt)->not->toBeNull();
});
