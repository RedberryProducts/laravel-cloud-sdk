<?php

use Illuminate\Support\LazyCollection;
use Redberry\LaravelCloudSdk\Data\Applications\ApplicationData;
use Redberry\LaravelCloudSdk\Data\Branches\BranchData;
use Redberry\LaravelCloudSdk\Data\Buckets\BucketData;
use Redberry\LaravelCloudSdk\Data\Caches\CacheData;
use Redberry\LaravelCloudSdk\Data\Databases\DatabaseData;
use Redberry\LaravelCloudSdk\Data\Deployments\DeploymentData;
use Redberry\LaravelCloudSdk\Data\Environments\CreateEnvironmentData;
use Redberry\LaravelCloudSdk\Data\Environments\DeleteEnvironmentVariablesData;
use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentData;
use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentLogEntryData;
use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentMetricsData;
use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentVariableData;
use Redberry\LaravelCloudSdk\Data\Environments\SetEnvironmentVariablesData;
use Redberry\LaravelCloudSdk\Data\Environments\UpdateEnvironmentData;
use Redberry\LaravelCloudSdk\Data\Instances\InstanceData;
use Redberry\LaravelCloudSdk\Data\WebsocketApplications\WebsocketApplicationData;
use Redberry\LaravelCloudSdk\Enums\EnvironmentVariableMethod;
use Redberry\LaravelCloudSdk\Enums\PhpVersion;
use Redberry\LaravelCloudSdk\LaravelCloud;
use Redberry\LaravelCloudSdk\Requests\Environments\CreateEnvironmentRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\DeleteEnvironmentRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\DeleteEnvironmentVariablesRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\GetEnvironmentLogsRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\GetEnvironmentMetricsRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\GetEnvironmentRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\SetEnvironmentVariablesRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\StartEnvironmentRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\StopEnvironmentRequest;
use Redberry\LaravelCloudSdk\Requests\Environments\UpdateEnvironmentRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Laravel\Facades\Saloon;

it('lists environments for an application', function () {
    Saloon::fake([
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/list'),
    ]);

    $result = (new LaravelCloud('token'))->environments('app-a15fd66f-e5b4-4f7c-ab47-e49a303d9bea');

    expect($result)->toBeInstanceOf(LazyCollection::class);
    $first = $result->first();
    expect($first)->toBeInstanceOf(EnvironmentData::class);
    expect($first->application)->toBeInstanceOf(ApplicationData::class);
    expect($first->branch)->toBeInstanceOf(BranchData::class);
    expect($first->deployments)->toHaveCount(5);
    expect($first->instances)->toHaveCount(2);
    expect($first->database)->toBeInstanceOf(DatabaseData::class);
    expect($first->cache)->toBeInstanceOf(CacheData::class);
    expect($first->buckets)->toHaveCount(1);
    expect($first->websocketApplication)->toBeInstanceOf(WebsocketApplicationData::class);
    Saloon::assertSent(ListEnvironmentsRequest::class);
});

it('retrieves a single environment by id', function () {
    Saloon::fake([
        GetEnvironmentRequest::class => new LaravelCloudFixture('environments/get'),
    ]);

    $result = (new LaravelCloud('token'))->environment('env-a15fd671-0b6a-401a-84bf-14105ce69023');

    Saloon::assertSent(GetEnvironmentRequest::class);
    expect($result)->toBeInstanceOf(EnvironmentData::class);
    expect($result->id)->toBe('env-a15fd671-0b6a-401a-84bf-14105ce69023');
    expect($result->name)->toBe('main');
    expect($result->application)->toBeInstanceOf(ApplicationData::class);
    expect($result->branch)->toBeInstanceOf(BranchData::class);
    expect($result->deployments)->toHaveCount(5);
    expect($result->deployments[0])->toBeInstanceOf(DeploymentData::class);
    expect($result->currentDeployment)->toBeInstanceOf(DeploymentData::class);
    expect($result->primaryDomain)->toBeNull();
    expect($result->instances)->toHaveCount(2);
    expect($result->instances[0])->toBeInstanceOf(InstanceData::class);
    expect($result->database)->toBeInstanceOf(DatabaseData::class);
    expect($result->cache)->toBeInstanceOf(CacheData::class);
    expect($result->buckets)->toHaveCount(1);
    expect($result->buckets[0])->toBeInstanceOf(BucketData::class);
    expect($result->websocketApplication)->toBeInstanceOf(WebsocketApplicationData::class);
});

it('creates an environment with named params', function () {
    Saloon::fake([
        CreateEnvironmentRequest::class => new LaravelCloudFixture('environments/create'),
    ]);

    $result = (new LaravelCloud('token'))->createEnvironment(
        applicationId: 'app-a14fe54f-42b2-431c-9b3a-876900975139',
        branch: 'main',
        name: 'production',
    );

    Saloon::assertSent(CreateEnvironmentRequest::class);
    expect($result)->toBeInstanceOf(EnvironmentData::class);
});

it('creates an environment via createEnvironmentWith()', function () {
    Saloon::fake([
        CreateEnvironmentRequest::class => new LaravelCloudFixture('environments/create'),
    ]);

    $result = (new LaravelCloud('token'))->createEnvironmentWith(
        'app-a14fe54f-42b2-431c-9b3a-876900975139',
        new CreateEnvironmentData(branch: 'main', name: 'production'),
    );

    Saloon::assertSent(CreateEnvironmentRequest::class);
    expect($result)->toBeInstanceOf(EnvironmentData::class);
});

it('updates an environment with named params', function () {
    Saloon::fake([
        UpdateEnvironmentRequest::class => new LaravelCloudFixture('environments/update'),
    ]);

    $result = (new LaravelCloud('token'))->updateEnvironment(
        'env-a14fe550-4e39-4ff2-8016-a20e4d32a996',
        name: 'updated-env',
        phpVersion: PhpVersion::V8_4,
    );

    Saloon::assertSent(UpdateEnvironmentRequest::class);
    expect($result)->toBeInstanceOf(EnvironmentData::class);
    expect($result->name)->toBe('updated-env');
});

it('updates an environment with a string php version', function () {
    Saloon::fake([
        UpdateEnvironmentRequest::class => new LaravelCloudFixture('environments/update'),
    ]);

    $result = (new LaravelCloud('token'))->updateEnvironment(
        'env-a14fe550-4e39-4ff2-8016-a20e4d32a996',
        phpVersion: '8.4',
    );

    Saloon::assertSent(UpdateEnvironmentRequest::class);
    expect($result)->toBeInstanceOf(EnvironmentData::class);
});

it('updates an environment via updateEnvironmentWith()', function () {
    Saloon::fake([
        UpdateEnvironmentRequest::class => new LaravelCloudFixture('environments/update'),
    ]);

    $result = (new LaravelCloud('token'))->updateEnvironmentWith(
        'env-a14fe550-4e39-4ff2-8016-a20e4d32a996',
        new UpdateEnvironmentData(name: 'updated-env'),
    );

    Saloon::assertSent(UpdateEnvironmentRequest::class);
    expect($result)->toBeInstanceOf(EnvironmentData::class);
});

it('gets environment metrics', function () {
    Saloon::fake([
        GetEnvironmentMetricsRequest::class => new LaravelCloudFixture('environments/metrics'),
    ]);

    $result = (new LaravelCloud('token'))->environmentMetrics('env-a15fd671-0b6a-401a-84bf-14105ce69023');

    Saloon::assertSent(GetEnvironmentMetricsRequest::class);
    expect($result)->toBeInstanceOf(EnvironmentMetricsData::class);
});

it('starts an environment', function () {
    Saloon::fake([
        StartEnvironmentRequest::class => new LaravelCloudFixture('environments/start'),
    ]);

    $result = (new LaravelCloud('token'))->startEnvironment('env-a15fd671-0b6a-401a-84bf-14105ce69023');

    Saloon::assertSent(StartEnvironmentRequest::class);
    expect($result)->toBeInstanceOf(DeploymentData::class);
});

it('stops an environment', function () {
    Saloon::fake([
        StopEnvironmentRequest::class => new LaravelCloudFixture('environments/stop'),
    ]);

    $result = (new LaravelCloud('token'))->stopEnvironment('env-a15fd671-0b6a-401a-84bf-14105ce69023');

    Saloon::assertSent(StopEnvironmentRequest::class);
    expect($result)->toBeInstanceOf(EnvironmentData::class);
});

it('deletes an environment', function () {
    Saloon::fake([
        DeleteEnvironmentRequest::class => new LaravelCloudFixture('environments/delete'),
    ]);

    (new LaravelCloud('token'))->deleteEnvironment('env-a14fe550-4e39-4ff2-8016-a20e4d32a996');

    Saloon::assertSent(DeleteEnvironmentRequest::class);
});

it('sets environment variables with named params', function () {
    Saloon::fake([
        SetEnvironmentVariablesRequest::class => new LaravelCloudFixture('environments/set-variables'),
    ]);

    $result = (new LaravelCloud('token'))->setEnvironmentVariables(
        'env-a14fe550-4e39-4ff2-8016-a20e4d32a996',
        method: EnvironmentVariableMethod::Append,
        variables: [new EnvironmentVariableData(key: 'SDK_TEST_VAR', value: 'sdk-test-value')],
    );

    Saloon::assertSent(SetEnvironmentVariablesRequest::class);
    expect($result)->toBeInstanceOf(EnvironmentData::class);
});

it('sets environment variables via setEnvironmentVariablesWith()', function () {
    Saloon::fake([
        SetEnvironmentVariablesRequest::class => new LaravelCloudFixture('environments/set-variables'),
    ]);

    $result = (new LaravelCloud('token'))->setEnvironmentVariablesWith(
        'env-a14fe550-4e39-4ff2-8016-a20e4d32a996',
        new SetEnvironmentVariablesData(
            method: EnvironmentVariableMethod::Append,
            variables: [new EnvironmentVariableData(key: 'SDK_TEST_VAR', value: 'sdk-test-value')],
        ),
    );

    Saloon::assertSent(SetEnvironmentVariablesRequest::class);
    expect($result)->toBeInstanceOf(EnvironmentData::class);
});

it('deletes environment variables with named params', function () {
    Saloon::fake([
        DeleteEnvironmentVariablesRequest::class => new LaravelCloudFixture('environments/delete-variables'),
    ]);

    $result = (new LaravelCloud('token'))->deleteEnvironmentVariables(
        'env-a14fe550-4e39-4ff2-8016-a20e4d32a996',
        keys: ['SDK_TEST_VAR'],
    );

    Saloon::assertSent(DeleteEnvironmentVariablesRequest::class);
    expect($result)->toBeInstanceOf(EnvironmentData::class);
});

it('deletes environment variables via deleteEnvironmentVariablesWith()', function () {
    Saloon::fake([
        DeleteEnvironmentVariablesRequest::class => new LaravelCloudFixture('environments/delete-variables'),
    ]);

    $result = (new LaravelCloud('token'))->deleteEnvironmentVariablesWith(
        'env-a14fe550-4e39-4ff2-8016-a20e4d32a996',
        new DeleteEnvironmentVariablesData(keys: ['SDK_TEST_VAR']),
    );

    Saloon::assertSent(DeleteEnvironmentVariablesRequest::class);
    expect($result)->toBeInstanceOf(EnvironmentData::class);
});

it('gets environment logs', function () {
    Saloon::fake([
        GetEnvironmentLogsRequest::class => new LaravelCloudFixture('environments/logs-paginated'),
    ]);

    $result = (new LaravelCloud('token'))->environmentLogs(
        'env-a15fd671-0b6a-401a-84bf-14105ce69023',
        '2026-04-08T14:02:51Z',
        '2026-04-09T14:02:51Z',
    );

    expect($result)->toBeInstanceOf(LazyCollection::class);
    expect($result->first())->toBeInstanceOf(EnvironmentLogEntryData::class);
    Saloon::assertSent(GetEnvironmentLogsRequest::class);
});
