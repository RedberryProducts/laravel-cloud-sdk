<?php

use Redberry\LaravelCloudSdk\Data\Applications\ApplicationData;
use Redberry\LaravelCloudSdk\Data\Applications\ApplicationRepositoryData;
use Redberry\LaravelCloudSdk\Data\Applications\CreateApplicationData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\SourceControlProvider;
use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Requests\Applications\CreateApplicationRequest;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $data = new CreateApplicationData(
        repository: 'RedberryProducts/redberry-automations',
        name: 'test-app',
        region: CloudRegion::US_EAST_1,
        sourceControlProviderType: SourceControlProvider::GITHUB,
    );
    $request = new CreateApplicationRequest($data);

    expect($request->resolveEndpoint())->toBe('/applications');
});

it('has the correct HTTP method', function () {
    $data = new CreateApplicationData(
        repository: 'RedberryProducts/redberry-automations',
        name: 'test-app',
        region: CloudRegion::US_EAST_1,
        sourceControlProviderType: SourceControlProvider::GITHUB,
    );
    $request = new CreateApplicationRequest($data);

    expect($request->getMethod())->toBe(Method::POST);
});

it('sends correct body', function () {
    $data = new CreateApplicationData(
        repository: 'RedberryProducts/redberry-automations',
        name: 'test-app',
        region: CloudRegion::US_EAST_1,
        sourceControlProviderType: SourceControlProvider::GITHUB,
    );
    $request = new CreateApplicationRequest($data);
    $body = $request->body()->all();

    expect($body['repository'])->toBe('RedberryProducts/redberry-automations');
    expect($body['name'])->toBe('test-app');
    expect($body['region'])->toBe('us-east-1');
    expect($body['source_control_provider_type'])->toBe('github');
});

it('creates an application and returns ApplicationData with all fields', function () {
    Saloon::fake([
        CreateApplicationRequest::class => new LaravelCloudFixture('applications/create'),
    ]);

    $data = new CreateApplicationData(
        repository: 'RedberryProducts/redberry-automations',
        name: 'test-app',
        region: CloudRegion::US_EAST_1,
        sourceControlProviderType: SourceControlProvider::GITHUB,
    );

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $response = $connector->send(new CreateApplicationRequest($data));

    Saloon::assertSent(CreateApplicationRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(ApplicationData::class);
    expect($dto->id)->toBeString();
    expect($dto->name)->toBe('test-app');
    expect($dto->slug)->toBeString();
    expect($dto->region)->toBe(CloudRegion::US_EAST_1);
    expect($dto->slackChannel)->toBeNull();
    expect($dto->avatarUrl)->toBeNull();
    expect($dto->repository)->toBeInstanceOf(ApplicationRepositoryData::class);
    expect($dto->repository->fullName)->toBeString();
    expect($dto->repository->defaultBranch)->toBeString();
    expect($dto->createdAt)->not->toBeNull();
});
