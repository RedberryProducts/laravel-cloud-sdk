<?php

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Data\Meta\IpAddressData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\LaravelCloud;
use Redberry\LaravelCloudSdk\Requests\Meta\ListIpAddressesRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Laravel\Facades\Saloon;

it('lists ip addresses', function () {
    Saloon::fake([
        ListIpAddressesRequest::class => new LaravelCloudFixture('meta/ip-addresses'),
    ]);

    $result = (new LaravelCloud('token'))->ipAddresses();

    Saloon::assertSent(ListIpAddressesRequest::class);
    expect($result)->toBeInstanceOf(Collection::class);
    expect($result->first())->toBeInstanceOf(IpAddressData::class);
});

it('lists ip addresses filtered by region enum', function () {
    Saloon::fake([
        ListIpAddressesRequest::class => new LaravelCloudFixture('meta/ip-addresses'),
    ]);

    $result = (new LaravelCloud('token'))->ipAddresses(CloudRegion::US_EAST_1);

    Saloon::assertSent(ListIpAddressesRequest::class);
    expect($result)->toBeInstanceOf(Collection::class);
    expect($result->first())->toBeInstanceOf(IpAddressData::class);
});

it('lists ip addresses filtered by region string', function () {
    Saloon::fake([
        ListIpAddressesRequest::class => new LaravelCloudFixture('meta/ip-addresses'),
    ]);

    $result = (new LaravelCloud('token'))->ipAddresses('us-east-1');

    Saloon::assertSent(ListIpAddressesRequest::class);
    expect($result)->toBeInstanceOf(Collection::class);
    expect($result->first())->toBeInstanceOf(IpAddressData::class);
});
