<?php

use App\Data\LaravelCloud\Applications\ApplicationData;
use App\Data\LaravelCloud\Applications\UpdateApplicationData;
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

it('sends correct body', function () {
    $data = new UpdateApplicationData(
        sourceControlProviderType: SourceControlProvider::GITHUB,
        name: 'updated-app',
    );
    $request = new UpdateApplicationRequest('app-123', $data);
    $body = $request->body()->all();

    expect($body['source_control_provider_type'])->toBe('github');
    expect($body['name'])->toBe('updated-app');
});

it('updates an application and returns ApplicationData', function () {
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
});
