<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentData;
use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentVariableData;
use Redberry\LaravelCloudSdk\Data\Environments\SetEnvironmentVariablesData;
use Redberry\LaravelCloudSdk\Enums\EnvironmentVariableMethod;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\SetEnvironmentVariablesRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $data = new SetEnvironmentVariablesData(method: EnvironmentVariableMethod::Append, variables: []);
    $request = new SetEnvironmentVariablesRequest('env-123', $data);

    expect($request->resolveEndpoint())->toBe('/environments/env-123/variables');
});

it('has the correct HTTP method', function () {
    $data = new SetEnvironmentVariablesData(method: EnvironmentVariableMethod::Append, variables: []);
    $request = new SetEnvironmentVariablesRequest('env-123', $data);

    expect($request->getMethod())->toBe(Method::POST);
});

it('implements HasBody', function () {
    $data = new SetEnvironmentVariablesData(method: EnvironmentVariableMethod::Append, variables: []);
    $request = new SetEnvironmentVariablesRequest('env-123', $data);

    expect($request)->toBeInstanceOf(HasBody::class);
});

it('sends correct body', function () {
    $data = new SetEnvironmentVariablesData(
        method: EnvironmentVariableMethod::Append,
        variables: [new EnvironmentVariableData(key: 'APP_ENV', value: 'production')],
    );
    $request = new SetEnvironmentVariablesRequest('env-123', $data);
    $body = $request->body()->all();

    expect($body['method'])->toBe('append');
    expect($body['variables'][0]['key'])->toBe('APP_ENV');
    expect($body['variables'][0]['value'])->toBe('production');
});

it('sets environment variables and returns EnvironmentData', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/list'),
        SetEnvironmentVariablesRequest::class => new LaravelCloudFixture('environments/set-variables'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()->first();
    $firstEnvironment = $connector->send(new ListEnvironmentsRequest($firstApplication->id))->dtoOrFail()->first();

    $data = new SetEnvironmentVariablesData(
        method: EnvironmentVariableMethod::Append,
        variables: [new EnvironmentVariableData(key: 'SDK_TEST_VAR', value: 'sdk-test-value')],
    );
    $response = $connector->send(new SetEnvironmentVariablesRequest($firstEnvironment->id, $data));

    Saloon::assertSent(SetEnvironmentVariablesRequest::class);
    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(EnvironmentData::class);
});
