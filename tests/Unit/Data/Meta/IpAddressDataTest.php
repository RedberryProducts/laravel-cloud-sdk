<?php

use Redberry\LaravelCloudSdk\Data\Meta\IpAddressData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;

it('can be constructed with all parameters', function () {
    $data = new IpAddressData(
        region: CloudRegion::UsEast1,
        ipv4: ['1.2.3.4', '5.6.7.8'],
        ipv6: ['2001:db8::1'],
    );

    expect($data->region)->toBe(CloudRegion::UsEast1);
    expect($data->ipv4)->toBe(['1.2.3.4', '5.6.7.8']);
    expect($data->ipv6)->toBe(['2001:db8::1']);
});

it('can be created from API response data', function () {
    $data = IpAddressData::fromResponse('us-east-1', [
        'ipv4' => ['1.2.3.4', '5.6.7.8'],
        'ipv6' => ['2001:db8::1', '2001:db8::2'],
    ]);

    expect($data)->toBeInstanceOf(IpAddressData::class);
    expect($data->region)->toBe(CloudRegion::UsEast1);
    expect($data->ipv4)->toBe(['1.2.3.4', '5.6.7.8']);
    expect($data->ipv6)->toBe(['2001:db8::1', '2001:db8::2']);
});

it('casts region string to CloudRegion enum', function () {
    $data = IpAddressData::fromResponse('eu-central-1', [
        'ipv4' => ['192.0.2.0'],
        'ipv6' => [],
    ]);

    expect($data->region)->toBeInstanceOf(CloudRegion::class);
    expect($data->region)->toBe(CloudRegion::EuCentral1);
});

it('falls back to raw string for unknown regions', function () {
    $data = IpAddressData::fromResponse('ap-unknown-9', [
        'ipv4' => ['10.0.0.1'],
        'ipv6' => [],
    ]);

    expect($data->region)->toBe('ap-unknown-9');
});
