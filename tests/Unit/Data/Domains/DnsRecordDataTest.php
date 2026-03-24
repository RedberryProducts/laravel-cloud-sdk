<?php

use Redberry\LaravelCloudSdk\Data\Domains\DnsRecordData;

it('builds from response attributes', function () {
    $data = DnsRecordData::fromResponse([
        'type' => 'CNAME',
        'name' => 'example.com',
        'value' => 'verify.cloudflare.com',
    ]);

    expect($data->type)->toBe('CNAME');
    expect($data->name)->toBe('example.com');
    expect($data->value)->toBe('verify.cloudflare.com');
});

it('stores all three fields', function () {
    $data = new DnsRecordData(
        type: 'TXT',
        name: '_cf-verify.example.com',
        value: 'abc123',
    );

    expect($data->type)->toBe('TXT');
    expect($data->name)->toBe('_cf-verify.example.com');
    expect($data->value)->toBe('abc123');
});
