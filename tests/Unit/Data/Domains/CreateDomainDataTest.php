<?php

use Redberry\LaravelCloudSdk\Data\Domains\CreateDomainData;
use Redberry\LaravelCloudSdk\Enums\DomainCloudflareStrategy;
use Redberry\LaravelCloudSdk\Enums\DomainRedirect;
use Redberry\LaravelCloudSdk\Enums\DomainVerificationMethod;
use Spatie\LaravelData\Optional;

it('requires name, wwwRedirect, and verificationMethod', function () {
    $data = new CreateDomainData(
        name: 'example.com',
        wwwRedirect: DomainRedirect::ROOT_TO_WWW,
        verificationMethod: DomainVerificationMethod::PRE_VERIFICATION,
    );

    $array = $data->toArray();

    expect($array['name'])->toBe('example.com');
    expect($array['www_redirect'])->toBe('root_to_www');
    expect($array['verification_method'])->toBe('pre_verification');
});

it('defaults optional fields to Optional', function () {
    $data = new CreateDomainData(
        name: 'example.com',
        wwwRedirect: DomainRedirect::ROOT_TO_WWW,
        verificationMethod: DomainVerificationMethod::PRE_VERIFICATION,
    );

    expect($data->cloudflareStrategy)->toBeInstanceOf(Optional::class);
    expect($data->wildcardEnabled)->toBeInstanceOf(Optional::class);
    expect($data->allowDowntime)->toBeInstanceOf(Optional::class);
});

it('serializes all optional fields when provided', function () {
    $data = new CreateDomainData(
        name: 'example.com',
        wwwRedirect: DomainRedirect::WWW_TO_ROOT,
        verificationMethod: DomainVerificationMethod::REAL_TIME,
        cloudflareStrategy: DomainCloudflareStrategy::DNS_PROXY,
        wildcardEnabled: true,
        allowDowntime: false,
    );

    $array = $data->toArray();

    expect($array['name'])->toBe('example.com');
    expect($array['www_redirect'])->toBe('www_to_root');
    expect($array['verification_method'])->toBe('real_time');
    expect($array['cloudflare_strategy'])->toBe('dns_proxy');
    expect($array['wildcard_enabled'])->toBeTrue();
    expect($array['allow_downtime'])->toBeFalse();
});

it('excludes unset optional fields', function () {
    $data = new CreateDomainData(
        name: 'example.com',
        wwwRedirect: DomainRedirect::ROOT_TO_WWW,
        verificationMethod: DomainVerificationMethod::PRE_VERIFICATION,
    );

    $array = $data->toArray();

    expect($array)->not->toHaveKey('cloudflare_strategy');
    expect($array)->not->toHaveKey('wildcard_enabled');
    expect($array)->not->toHaveKey('allow_downtime');
});
