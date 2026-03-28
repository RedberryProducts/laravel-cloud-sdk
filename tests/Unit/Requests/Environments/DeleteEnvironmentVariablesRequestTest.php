<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Environments\DeleteEnvironmentVariablesData;
use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentData;
use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentVariableData;
use Redberry\LaravelCloudSdk\Data\Environments\SetEnvironmentVariablesData;
use Redberry\LaravelCloudSdk\Enums\EnvironmentVariableMethod;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\DeleteEnvironmentVariablesRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\SetEnvironmentVariablesRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $data = new DeleteEnvironmentVariablesData(keys: ['APP_ENV']);
    $request = new DeleteEnvironmentVariablesRequest('env-123', $data);

    expect($request->resolveEndpoint())->toBe('/environments/env-123/variables/delete');
});

it('has the correct HTTP method', function () {
    $data = new DeleteEnvironmentVariablesData(keys: ['APP_ENV']);
    $request = new DeleteEnvironmentVariablesRequest('env-123', $data);

    expect($request->getMethod())->toBe(Method::POST);
});

it('implements HasBody', function () {
    $data = new DeleteEnvironmentVariablesData(keys: ['APP_ENV']);
    $request = new DeleteEnvironmentVariablesRequest('env-123', $data);

    expect($request)->toBeInstanceOf(HasBody::class);
});

it('sends correct body', function () {
    $data = new DeleteEnvironmentVariablesData(keys: ['APP_ENV', 'APP_KEY']);
    $request = new DeleteEnvironmentVariablesRequest('env-123', $data);
    $body = $request->body()->all();

    expect($body['keys'])->toBe(['APP_ENV', 'APP_KEY']);
});

it('deletes environment variables and returns EnvironmentData', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/list'),
        SetEnvironmentVariablesRequest::class => new LaravelCloudFixture('environments/set-variables'),
        DeleteEnvironmentVariablesRequest::class => new LaravelCloudFixture('environments/delete-variables'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()->first();
    $firstEnvironment = $connector->send(new ListEnvironmentsRequest($firstApplication->id))->dtoOrFail()->first();

    $connector->send(new SetEnvironmentVariablesRequest($firstEnvironment->id, new SetEnvironmentVariablesData(
        method: EnvironmentVariableMethod::Append,
        variables: [new EnvironmentVariableData(key: 'SDK_TEST_VAR', value: 'sdk-test-value')],
    )));

    $data = new DeleteEnvironmentVariablesData(keys: ['SDK_TEST_VAR']);
    $response = $connector->send(new DeleteEnvironmentVariablesRequest($firstEnvironment->id, $data));

    Saloon::assertSent(DeleteEnvironmentVariablesRequest::class);
    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(EnvironmentData::class);
});
