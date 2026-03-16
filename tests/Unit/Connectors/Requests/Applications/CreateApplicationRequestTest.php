<?php

use App\Data\LaravelCloud\Applications\ApplicationData;
use App\Data\LaravelCloud\Applications\CreateApplicationData;
use App\Enums\LaravelCloud\CloudRegion;
use App\Enums\LaravelCloud\SourceControlProvider;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\Applications\CreateApplicationRequest;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

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

it('creates an application and returns ApplicationData', function () {
    Saloon::fake([
        CreateApplicationRequest::class => new LaravelCloudFixture('applications/create'),
    ]);

    $data = new CreateApplicationData(
        repository: 'RedberryProducts/redberry-automations',
        name: 'test-app',
        region: CloudRegion::US_EAST_1,
        sourceControlProviderType: SourceControlProvider::GITHUB,
    );

    $connector = new LaravelCloudConnector(config('laravel-cloud.token'));
    $response = $connector->send(new CreateApplicationRequest($data));

    Saloon::assertSent(CreateApplicationRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(ApplicationData::class);
});
