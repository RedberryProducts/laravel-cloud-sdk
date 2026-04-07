<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Commands\CommandData;
use Redberry\LaravelCloudSdk\Data\Commands\RunCommandData;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\Commands\GetCommandRequest;
use Redberry\LaravelCloudSdk\Requests\Commands\RunCommandRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new GetCommandRequest('cmd-123');

    expect($request->resolveEndpoint())->toBe('/commands/cmd-123');
});

it('has the correct HTTP method', function () {
    $request = new GetCommandRequest('cmd-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('gets a command and returns CommandData', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/list'),
        RunCommandRequest::class => new LaravelCloudFixture('commands/create'),
        GetCommandRequest::class => new LaravelCloudFixture('commands/get'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()[0];
    $firstEnvironment = $connector->send(new ListEnvironmentsRequest($firstApplication->id))->dtoOrFail()[0];

    $createdCommand = $connector->send(new RunCommandRequest($firstEnvironment->id, new RunCommandData(command: 'php artisan cache:clear')))->dtoOrFail();
    $response = $connector->send(new GetCommandRequest($createdCommand->id));

    Saloon::assertSent(GetCommandRequest::class);
    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(CommandData::class);
});
