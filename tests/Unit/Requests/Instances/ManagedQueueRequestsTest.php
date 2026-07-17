<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Instances\InstanceData;
use Redberry\LaravelCloudSdk\Enums\InstanceType;
use Redberry\LaravelCloudSdk\Enums\ManagedQueueStatus;
use Redberry\LaravelCloudSdk\Requests\Instances\PauseManagedQueueRequest;
use Redberry\LaravelCloudSdk\Requests\Instances\PurgeManagedQueueRequest;
use Redberry\LaravelCloudSdk\Requests\Instances\ResumeManagedQueueRequest;
use Redberry\LaravelCloudSdk\Requests\Instances\SetDefaultManagedQueueRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Exceptions\Request\Statuses\NotFoundException;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;

it('resolves managed queue action endpoints correctly', function (string $requestClass, string $endpoint) {
    $request = new $requestClass('inst-123');

    expect($request->resolveEndpoint())->toBe($endpoint);
    expect($request->getMethod())->toBe(Method::POST);
})->with([
    [PauseManagedQueueRequest::class, '/instances/inst-123/pause'],
    [ResumeManagedQueueRequest::class, '/instances/inst-123/resume'],
    [PurgeManagedQueueRequest::class, '/instances/inst-123/purge'],
    [SetDefaultManagedQueueRequest::class, '/instances/inst-123/default'],
]);

it('hydrates managed queue instance fields from action responses', function () {
    Saloon::fake([
        PauseManagedQueueRequest::class => new LaravelCloudFixture('instances/managed-queue-pause'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $response = $connector->send(new PauseManagedQueueRequest('inst-9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d'));

    Saloon::assertSent(PauseManagedQueueRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(InstanceData::class);
    expect($dto->type)->toBe(InstanceType::ManagedQueue);
    expect($dto->queueStatus)->toBe(ManagedQueueStatus::Available);
    expect($dto->paused)->toBeTrue();
    expect($dto->visibilityTimeout)->toBe(60);
    expect($dto->pollingInterval)->toBe(20);
});

it('throws not found for invalid managed queue instance ids', function (string $requestClass) {
    Saloon::fake([
        $requestClass => MockResponse::make([], 404),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));

    $connector->send(new $requestClass('invalid-instance-id'))->throw();
})->with([
    PauseManagedQueueRequest::class,
    ResumeManagedQueueRequest::class,
    PurgeManagedQueueRequest::class,
    SetDefaultManagedQueueRequest::class,
])->throws(NotFoundException::class);
