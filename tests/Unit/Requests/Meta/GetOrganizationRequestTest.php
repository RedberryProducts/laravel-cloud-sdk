<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Meta\OrganizationData;
use Redberry\LaravelCloudSdk\Requests\Meta\GetOrganizationRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new GetOrganizationRequest;

    expect($request->resolveEndpoint())->toBe('/meta/organization');
});

it('has the correct HTTP method', function () {
    $request = new GetOrganizationRequest;

    expect($request->getMethod())->toBe(Method::GET);
});

it('retrieves organization and returns OrganizationData', function () {
    Saloon::fake([
        GetOrganizationRequest::class => new LaravelCloudFixture('meta/organization'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $response = $connector->send(new GetOrganizationRequest);

    Saloon::assertSent(GetOrganizationRequest::class);

    $result = $response->dtoOrFail();
    expect($result)->toBeInstanceOf(OrganizationData::class);
    expect($result->id)->toBeString();
    expect($result->name)->toBeString();
    expect($result->slug)->toBeString();
});
