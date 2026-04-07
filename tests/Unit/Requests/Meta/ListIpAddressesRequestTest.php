<?php

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Meta\IpAddressData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Requests\Meta\ListIpAddressesRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new ListIpAddressesRequest;

    expect($request->resolveEndpoint())->toBe('/ip');
});

it('has the correct HTTP method', function () {
    $request = new ListIpAddressesRequest;

    expect($request->getMethod())->toBe(Method::GET);
});

it('sends no query params when no region is specified', function () {
    $request = new ListIpAddressesRequest;

    expect($request->defaultQuery())->toBe([]);
});

it('sends the region query param when a CloudRegion enum is given', function () {
    $request = new ListIpAddressesRequest(CloudRegion::US_EAST_1);

    expect($request->defaultQuery())->toBe(['region' => 'us-east-1']);
});

it('sends the region query param when a string is given', function () {
    $request = new ListIpAddressesRequest('us-east-1');

    expect($request->defaultQuery())->toBe(['region' => 'us-east-1']);
});

it('lists ip addresses and returns IpAddressData collection keyed by region', function () {
    Saloon::fake([
        ListIpAddressesRequest::class => new LaravelCloudFixture('meta/ip-addresses'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $dto = $connector->send(new ListIpAddressesRequest)->dtoOrFail();

    Saloon::assertSent(ListIpAddressesRequest::class);
    expect($dto)->toBeInstanceOf(Collection::class);
    expect($dto->first())->toBeInstanceOf(IpAddressData::class);
    expect($dto->first()->region)->toBeInstanceOf(CloudRegion::class);
    expect($dto->first()->ipv4)->toBeArray();
    expect($dto->first()->ipv6)->toBeArray();
});
