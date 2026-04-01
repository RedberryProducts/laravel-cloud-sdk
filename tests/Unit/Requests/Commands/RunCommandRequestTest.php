<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Commands\CommandData;
use Redberry\LaravelCloudSdk\Data\Commands\RunCommandData;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\Commands\RunCommandRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $data = new RunCommandData(command: 'php artisan cache:clear');
    $request = new RunCommandRequest('env-123', $data);

    expect($request->resolveEndpoint())->toBe('/environments/env-123/commands');
});

it('has the correct HTTP method', function () {
    $data = new RunCommandData(command: 'php artisan cache:clear');
    $request = new RunCommandRequest('env-123', $data);

    expect($request->getMethod())->toBe(Method::POST);
});

it('implements HasBody', function () {
    $data = new RunCommandData(command: 'php artisan cache:clear');
    $request = new RunCommandRequest('env-123', $data);

    expect($request)->toBeInstanceOf(HasBody::class);
});

it('sends correct body', function () {
    $data = new RunCommandData(command: 'php artisan migrate:status');
    $request = new RunCommandRequest('env-123', $data);
    $body = $request->body()->all();

    expect($body['command'])->toBe('php artisan migrate:status');
});

it('runs a command and returns CommandData', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/list'),
        RunCommandRequest::class => new LaravelCloudFixture('commands/create'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()->first();
    $firstEnvironment = $connector->send(new ListEnvironmentsRequest($firstApplication->id))->dtoOrFail()->first();

    $data = new RunCommandData(command: 'php artisan cache:clear');
    $response = $connector->send(new RunCommandRequest($firstEnvironment->id, $data));

    Saloon::assertSent(RunCommandRequest::class);
    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(CommandData::class);
});
