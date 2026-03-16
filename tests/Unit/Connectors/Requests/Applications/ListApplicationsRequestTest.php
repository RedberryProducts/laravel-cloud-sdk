<?php

use App\Data\LaravelCloud\Applications\ApplicationData;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\Applications\ListApplicationsRequest;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $request = new ListApplicationsRequest;

    expect($request->resolveEndpoint())->toBe('/applications');
});

it('has the correct HTTP method', function () {
    $request = new ListApplicationsRequest;

    expect($request->getMethod())->toBe(Method::GET);
});

it('lists applications and returns ApplicationData collection', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud.token'));
    $response = $connector->send(new ListApplicationsRequest);

    Saloon::assertSent(ListApplicationsRequest::class);
    expect($response->getPsrRequest()->getMethod())->toBe('GET');
    expect($response->getPsrRequest()->getUri()->getPath())->toBe('/api/applications');

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(Collection::class);
    expect($dto->first())->toBeInstanceOf(ApplicationData::class);
});
