<?php

use Redberry\LaravelCloudSdk\Data\Domains\CreateDomainData;
use Redberry\LaravelCloudSdk\Enums\DomainCloudflareStrategy;
use Redberry\LaravelCloudSdk\Enums\DomainRedirect;
use Redberry\LaravelCloudSdk\Enums\DomainVerificationMethod;
use Spatie\LaravelData\Optional;

it('requires name, wwwRedirect, and verificationMethod', function () {
    $data = new CreateDomainData(
        name: 'example.com',
        wwwRedirect: DomainRedirect::RootToWww,
        verificationMethod: DomainVerificationMethod::PreVerification,
    );

    $array = $data->toArray();

    expect($array['name'])->toBe('example.com');
    expect($array['www_redirect'])->toBe('root_to_www');
    expect($array['verification_method'])->toBe('pre_verification');
});

it('defaults optional fields to Optional', function () {
    $data = new CreateDomainData(
        name: 'example.com',
        wwwRedirect: DomainRedirect::RootToWww,
        verificationMethod: DomainVerificationMethod::PreVerification,
    );

    expect($data->cloudflareStrategy)->toBeInstanceOf(Optional::class);
    expect($data->wildcardEnabled)->toBeInstanceOf(Optional::class);
    expect($data->allowDowntime)->toBeInstanceOf(Optional::class);
});

it('serializes all optional fields when provided', function () {
    $data = new CreateDomainData(
        name: 'example.com',
        wwwRedirect: DomainRedirect::WwwToRoot,
        verificationMethod: DomainVerificationMethod::RealTime,
        cloudflareStrategy: DomainCloudflareStrategy::DnsProxy,
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
        wwwRedirect: DomainRedirect::RootToWww,
        verificationMethod: DomainVerificationMethod::PreVerification,
    );

    $array = $data->toArray();

    expect($array)->not->toHaveKey('cloudflare_strategy');
    expect($array)->not->toHaveKey('wildcard_enabled');
    expect($array)->not->toHaveKey('allow_downtime');
});
