<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Applications\ApplicationData;
use Redberry\LaravelCloudSdk\Data\Applications\ApplicationRepositoryData;
use Redberry\LaravelCloudSdk\Data\Applications\UpdateApplicationData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\SourceControlProvider;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\Applications\UpdateApplicationRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $data = new UpdateApplicationData(name: 'updated-app');
    $request = new UpdateApplicationRequest('app-123', $data);

    expect($request->resolveEndpoint())->toBe('/applications/app-123');
});

it('has the correct HTTP method', function () {
    $data = new UpdateApplicationData(name: 'updated-app');
    $request = new UpdateApplicationRequest('app-123', $data);

    expect($request->getMethod())->toBe(Method::PATCH);
});

it('sends correct body with all optional fields', function () {
    $data = new UpdateApplicationData(
        sourceControlProviderType: SourceControlProvider::Github,
        name: 'updated-app',
        slug: 'updated-app-slug',
        defaultEnvironmentId: 'env-123',
        repository: 'RedberryProducts/redberry-automations',
        slackChannel: '#deployments',
    );
    $request = new UpdateApplicationRequest('app-123', $data);
    $body = $request->body()->all();

    expect($body['source_control_provider_type'])->toBe('github');
    expect($body['name'])->toBe('updated-app');
    expect($body['slug'])->toBe('updated-app-slug');
    expect($body['default_environment_id'])->toBe('env-123');
    expect($body['repository'])->toBe('RedberryProducts/redberry-automations');
    expect($body['slack_channel'])->toBe('#deployments');
});

it('excludes unset optional fields from body', function () {
    $data = new UpdateApplicationData(name: 'updated-app');
    $request = new UpdateApplicationRequest('app-123', $data);
    $body = $request->body()->all();

    expect($body)->toHaveKey('name');
    expect($body)->not->toHaveKey('source_control_provider_type');
    expect($body)->not->toHaveKey('slug');
    expect($body)->not->toHaveKey('default_environment_id');
    expect($body)->not->toHaveKey('repository');
    expect($body)->not->toHaveKey('slack_channel');
});

it('updates an application and returns ApplicationData with all fields', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()[0];

    Saloon::fake([
        UpdateApplicationRequest::class => new LaravelCloudFixture('applications/update'),
    ]);

    $data = new UpdateApplicationData(name: 'updated-app');
    $response = $connector->send(new UpdateApplicationRequest($firstApplication->id, $data));

    Saloon::assertSent(UpdateApplicationRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(ApplicationData::class);
    expect($dto->id)->toBeString();
    expect($dto->name)->toBe('updated-app');
    expect($dto->slug)->toBeString();
    expect($dto->region)->toBe(CloudRegion::UsEast1);
    expect($dto->slackChannel)->toBeNull();
    expect($dto->avatarUrl)->toBeNull();
    expect($dto->repository)->toBeInstanceOf(ApplicationRepositoryData::class);
    expect($dto->repository->fullName)->toBeString();
    expect($dto->repository->defaultBranch)->toBeString();
    expect($dto->createdAt)->not->toBeNull();
});
