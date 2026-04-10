<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\BackgroundProcesses\BackgroundProcessData;
use Redberry\LaravelCloudSdk\Data\BackgroundProcesses\CreateBackgroundProcessData;
use Redberry\LaravelCloudSdk\Enums\DaemonType;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\BackgroundProcesses\CreateBackgroundProcessRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Redberry\LaravelCloudSdk\Requests\Instances\ListInstancesRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $data = new CreateBackgroundProcessData(type: DaemonType::Worker, processes: 1);
    $request = new CreateBackgroundProcessRequest('inst-123', $data);

    expect($request->resolveEndpoint())->toBe('/instances/inst-123/background-processes');
});

it('has the correct HTTP method', function () {
    $data = new CreateBackgroundProcessData(type: DaemonType::Worker, processes: 1);
    $request = new CreateBackgroundProcessRequest('inst-123', $data);

    expect($request->getMethod())->toBe(Method::POST);
});

it('implements HasBody', function () {
    $data = new CreateBackgroundProcessData(type: DaemonType::Worker, processes: 1);
    $request = new CreateBackgroundProcessRequest('inst-123', $data);

    expect($request)->toBeInstanceOf(HasBody::class);
});

it('sends correct body for custom type', function () {
    $data = new CreateBackgroundProcessData(
        type: DaemonType::Custom,
        processes: 2,
        command: 'php artisan my:command',
    );
    $request = new CreateBackgroundProcessRequest('inst-123', $data);
    $body = $request->body()->all();

    expect($body['type'])->toBe('custom');
    expect($body['processes'])->toBe(2);
    expect($body['command'])->toBe('php artisan my:command');
});

it('creates a custom background process and returns BackgroundProcessData', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/list'),
        ListInstancesRequest::class => new LaravelCloudFixture('instances/list'),
        CreateBackgroundProcessRequest::class => new LaravelCloudFixture('background-processes/create'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()[0];
    $firstEnvironment = $connector->send(new ListEnvironmentsRequest($firstApplication->id))->dtoOrFail()[0];
    $firstInstance = $connector->send(new ListInstancesRequest($firstEnvironment->id))->dtoOrFail()[0];

    $data = new CreateBackgroundProcessData(type: DaemonType::Custom, processes: 1, command: 'php artisan my:command');
    $response = $connector->send(new CreateBackgroundProcessRequest($firstInstance->id, $data));

    Saloon::assertSent(CreateBackgroundProcessRequest::class);
    expect($response->dtoOrFail())->toBeInstanceOf(BackgroundProcessData::class);
});
