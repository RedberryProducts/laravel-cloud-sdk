<?php

use App\Data\LaravelCloud\Domains\DnsRecordData;
use App\Data\LaravelCloud\Domains\DomainData;
use App\Enums\LaravelCloud\DomainCloudflareStrategy;
use App\Enums\LaravelCloud\DomainRedirect;
use App\Enums\LaravelCloud\DomainStatus;
use App\Enums\LaravelCloud\DomainType;
use Carbon\CarbonImmutable;

it('builds from response attributes', function () {
    $data = DomainData::fromResponse([
        'name' => 'example.com',
        'type' => 'root',
        'hostname_status' => 'verified',
        'ssl_status' => 'pending',
        'origin_status' => 'disabled',
        'wildcard_enabled' => false,
        'dns_records' => [],
    ], 'domain-abc');

    expect($data->id)->toBe('domain-abc');
    expect($data->name)->toBe('example.com');
    expect($data->type)->toBe(DomainType::ROOT);
    expect($data->hostnameStatus)->toBe(DomainStatus::VERIFIED);
    expect($data->sslStatus)->toBe(DomainStatus::PENDING);
    expect($data->originStatus)->toBe(DomainStatus::DISABLED);
    expect($data->wildcardEnabled)->toBeFalse();
    expect($data->redirect)->toBeNull();
    expect($data->cloudflareStrategy)->toBeNull();
    expect($data->downtime)->toBeNull();
    expect($data->actionRequired)->toBeNull();
    expect($data->dnsRecords)->toBe([]);
    expect($data->lastVerifiedAt)->toBeNull();
    expect($data->createdAt)->toBeNull();
});

it('builds with optional fields set', function () {
    $data = DomainData::fromResponse([
        'name' => 'www.example.com',
        'type' => 'www',
        'hostname_status' => 'verified',
        'ssl_status' => 'verified',
        'origin_status' => 'verified',
        'redirect' => 'www_to_root',
        'cloudflare_strategy' => 'dns_proxy',
        'downtime' => false,
        'wildcard_enabled' => true,
        'action_required' => 'verify_dns',
        'dns_records' => [],
        'last_verified_at' => '2024-01-10T08:00:00Z',
        'created_at' => '2024-01-01T00:00:00Z',
    ], 'domain-xyz');

    expect($data->redirect)->toBe(DomainRedirect::WWW_TO_ROOT);
    expect($data->cloudflareStrategy)->toBe(DomainCloudflareStrategy::DNS_PROXY);
    expect($data->downtime)->toBeFalse();
    expect($data->wildcardEnabled)->toBeTrue();
    expect($data->actionRequired)->toBe('verify_dns');
    expect($data->lastVerifiedAt)->toBeInstanceOf(CarbonImmutable::class);
    expect($data->lastVerifiedAt->toDateString())->toBe('2024-01-10');
    expect($data->createdAt)->toBeInstanceOf(CarbonImmutable::class);
    expect($data->createdAt->toDateString())->toBe('2024-01-01');
});

it('parses dns_records into keyed arrays of DnsRecordData', function () {
    $data = DomainData::fromResponse([
        'name' => 'example.com',
        'type' => 'root',
        'hostname_status' => 'pending',
        'ssl_status' => 'pending',
        'origin_status' => 'pending',
        'wildcard_enabled' => false,
        'dns_records' => [
            // ssl is an array of records
            'ssl' => [
                ['type' => 'TXT', 'name' => 'example.com', 'value' => 'verify-xyz'],
            ],
            'pre_verification' => null,
            // origin is a single record object
            'origin' => ['type' => 'A', 'name' => 'example.com', 'value' => '1.2.3.4'],
        ],
    ], 'domain-dns');

    // ssl is an array of records
    expect($data->dnsRecords)->toHaveKey('ssl');
    expect($data->dnsRecords['ssl'])->toBeArray();
    expect($data->dnsRecords['ssl'][0])->toBeInstanceOf(DnsRecordData::class);
    expect($data->dnsRecords['ssl'][0]->type)->toBe('TXT');
    expect($data->dnsRecords['ssl'][0]->value)->toBe('verify-xyz');

    // null stays null
    expect($data->dnsRecords['pre_verification'])->toBeNull();

    // single record gets wrapped in an array
    expect($data->dnsRecords['origin'])->toBeArray();
    expect($data->dnsRecords['origin'][0])->toBeInstanceOf(DnsRecordData::class);
    expect($data->dnsRecords['origin'][0]->type)->toBe('A');
});
