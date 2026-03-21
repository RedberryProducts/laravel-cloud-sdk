<?php

use App\Data\LaravelCloud\Environments\CreateEnvironmentData;
use App\Data\LaravelCloud\Environments\EnvironmentData;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\Applications\ListApplicationsRequest;
use App\Http\Integrations\LaravelCloud\Requests\Environments\CreateEnvironmentRequest;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $data = new CreateEnvironmentData(branch: 'main', name: 'staging');
    $request = new CreateEnvironmentRequest('app-123', $data);

    expect($request->resolveEndpoint())->toBe('/applications/app-123/environments');
});

it('has the correct HTTP method', function () {
    $data = new CreateEnvironmentData(branch: 'main', name: 'staging');
    $request = new CreateEnvironmentRequest('app-123', $data);

    expect($request->getMethod())->toBe(Method::POST);
});

it('implements HasBody', function () {
    $data = new CreateEnvironmentData(branch: 'main', name: 'staging');
    $request = new CreateEnvironmentRequest('app-123', $data);

    expect($request)->toBeInstanceOf(HasBody::class);
});

it('sends correct body', function () {
    $data = new CreateEnvironmentData(branch: 'main', name: 'staging');
    $request = new CreateEnvironmentRequest('app-123', $data);
    $body = $request->body()->all();

    expect($body['branch'])->toBe('main');
    expect($body['name'])->toBe('staging');
});

it('creates an environment and returns EnvironmentData', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()->first();

    Saloon::fake([
        CreateEnvironmentRequest::class => new LaravelCloudFixture('environments/create'),
    ]);

    $data = new CreateEnvironmentData(branch: 'main', name: 'staging');
    $response = $connector->send(new CreateEnvironmentRequest($firstApplication->id, $data));

    Saloon::assertSent(CreateEnvironmentRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(EnvironmentData::class);
    expect($dto->name)->toBeString();
});
