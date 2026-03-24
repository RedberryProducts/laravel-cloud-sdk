<?php

use App\Data\LaravelCloud\Applications\ApplicationData;
use App\Data\LaravelCloud\Applications\ApplicationRepositoryData;
use App\Data\LaravelCloud\Applications\UpdateApplicationData;
use App\Enums\LaravelCloud\CloudRegion;
use App\Enums\LaravelCloud\SourceControlProvider;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\Applications\ListApplicationsRequest;
use App\Http\Integrations\LaravelCloud\Requests\Applications\UpdateApplicationRequest;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

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
        sourceControlProviderType: SourceControlProvider::GITHUB,
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

    $connector = new LaravelCloudConnector(config('laravel-cloud.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()->first();

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
    expect($dto->region)->toBe(CloudRegion::US_EAST_1);
    expect($dto->slackChannel)->toBeNull();
    expect($dto->avatarUrl)->toBeNull();
    expect($dto->repository)->toBeInstanceOf(ApplicationRepositoryData::class);
    expect($dto->repository->fullName)->toBeString();
    expect($dto->repository->defaultBranch)->toBeString();
    expect($dto->createdAt)->not->toBeNull();
});
