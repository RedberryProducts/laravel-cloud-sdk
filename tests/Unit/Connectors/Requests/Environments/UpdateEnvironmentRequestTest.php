<?php

use App\Data\LaravelCloud\Environments\EnvironmentData;
use App\Data\LaravelCloud\Environments\UpdateEnvironmentData;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\Applications\ListApplicationsRequest;
use App\Http\Integrations\LaravelCloud\Requests\Environments\ListEnvironmentsRequest;
use App\Http\Integrations\LaravelCloud\Requests\Environments\UpdateEnvironmentRequest;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $data = new UpdateEnvironmentData(name: 'updated-env');
    $request = new UpdateEnvironmentRequest('env-123', $data);

    expect($request->resolveEndpoint())->toBe('/environments/env-123');
});

it('has the correct HTTP method', function () {
    $data = new UpdateEnvironmentData(name: 'updated-env');
    $request = new UpdateEnvironmentRequest('env-123', $data);

    expect($request->getMethod())->toBe(Method::PATCH);
});

it('sends correct body', function () {
    $data = new UpdateEnvironmentData(name: 'updated-env', usesPushToDeploy: false);
    $request = new UpdateEnvironmentRequest('env-123', $data);
    $body = $request->body()->all();

    expect($body['name'])->toBe('updated-env');
    expect($body['uses_push_to_deploy'])->toBeFalse();
});

it('updates an environment and returns EnvironmentData', function () {
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
        UpdateEnvironmentRequest::class => new LaravelCloudFixture('environments/update'),
    ]);

    $data = new UpdateEnvironmentData(name: 'updated-env');
    $response = $connector->send(new UpdateEnvironmentRequest($firstEnvironment->id, $data));

    Saloon::assertSent(UpdateEnvironmentRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(EnvironmentData::class);
});
