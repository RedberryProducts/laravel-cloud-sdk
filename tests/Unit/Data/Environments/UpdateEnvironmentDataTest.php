<?php

use App\Data\LaravelCloud\Environments\FilesystemKeyData;
use App\Data\LaravelCloud\Environments\HstsData;
use App\Data\LaravelCloud\Environments\UpdateEnvironmentData;
use App\Enums\LaravelCloud\CacheStrategy;
use App\Enums\LaravelCloud\EnvironmentColor;
use App\Enums\LaravelCloud\FirewallRateLimitLevel;
use App\Enums\LaravelCloud\NodeVersion;
use App\Enums\LaravelCloud\PhpVersion;
use App\Enums\LaravelCloud\ResponseHeadersContentType;
use App\Enums\LaravelCloud\ResponseHeadersFrame;
use App\Enums\LaravelCloud\ResponseHeadersRobotsTag;
use Spatie\LaravelData\Optional;

it('defaults all parameters to Optional', function () {
    $data = new UpdateEnvironmentData;

    expect($data->name)->toBeInstanceOf(Optional::class);
    expect($data->slug)->toBeInstanceOf(Optional::class);
    expect($data->color)->toBeInstanceOf(Optional::class);
    expect($data->branch)->toBeInstanceOf(Optional::class);
    expect($data->phpVersion)->toBeInstanceOf(Optional::class);
    expect($data->nodeVersion)->toBeInstanceOf(Optional::class);
    expect($data->buildCommand)->toBeInstanceOf(Optional::class);
    expect($data->deployCommand)->toBeInstanceOf(Optional::class);
    expect($data->usesPushToDeploy)->toBeInstanceOf(Optional::class);
    expect($data->usesDeployHook)->toBeInstanceOf(Optional::class);
    expect($data->usesOctane)->toBeInstanceOf(Optional::class);
    expect($data->usesVanityDomain)->toBeInstanceOf(Optional::class);
    expect($data->timeout)->toBeInstanceOf(Optional::class);
    expect($data->sleepTimeout)->toBeInstanceOf(Optional::class);
    expect($data->shutdownTimeout)->toBeInstanceOf(Optional::class);
    expect($data->usesPurgeEdgeCacheOnDeploy)->toBeInstanceOf(Optional::class);
    expect($data->nightwatchToken)->toBeInstanceOf(Optional::class);
    expect($data->cacheStrategy)->toBeInstanceOf(Optional::class);
    expect($data->responseHeadersFrame)->toBeInstanceOf(Optional::class);
    expect($data->responseHeadersContentType)->toBeInstanceOf(Optional::class);
    expect($data->responseHeadersRobotsTag)->toBeInstanceOf(Optional::class);
    expect($data->responseHeadersHsts)->toBeInstanceOf(Optional::class);
    expect($data->filesystemKeys)->toBeInstanceOf(Optional::class);
    expect($data->firewallRateLimitLevel)->toBeInstanceOf(Optional::class);
    expect($data->firewallUnderAttackMode)->toBeInstanceOf(Optional::class);
    expect($data->databaseSchemaId)->toBeInstanceOf(Optional::class);
    expect($data->cacheId)->toBeInstanceOf(Optional::class);
    expect($data->websocketApplicationId)->toBeInstanceOf(Optional::class);
});

it('serializes set fields as snake_case and excludes unset optionals', function () {
    $data = new UpdateEnvironmentData(
        name: 'staging',
        color: EnvironmentColor::Blue,
        phpVersion: PhpVersion::V8_4,
        nodeVersion: NodeVersion::V22,
        usesPushToDeploy: true,
        cacheStrategy: CacheStrategy::Bypass,
        responseHeadersFrame: ResponseHeadersFrame::Deny,
        responseHeadersContentType: ResponseHeadersContentType::Nosniff,
        responseHeadersRobotsTag: ResponseHeadersRobotsTag::IndexFollow,
        firewallRateLimitLevel: FirewallRateLimitLevel::Throttle,
        firewallUnderAttackMode: false,
    );

    $array = $data->toArray();

    expect($array['name'])->toBe('staging');
    expect($array['color'])->toBe('blue');
    expect($array['php_version'])->toBe('8.4:1');
    expect($array['node_version'])->toBe('22');
    expect($array['uses_push_to_deploy'])->toBeTrue();
    expect($array['cache_strategy'])->toBe('bypass');
    expect($array['response_headers_frame'])->toBe('deny');
    expect($array['response_headers_content_type'])->toBe('nosniff');
    expect($array['response_headers_robots_tag'])->toBe('index, follow');
    expect($array['firewall_rate_limit_level'])->toBe('throttle');
    expect($array['firewall_under_attack_mode'])->toBeFalse();

    expect($array)->not->toHaveKey('build_command');
    expect($array)->not->toHaveKey('nightwatch_token');
    expect($array)->not->toHaveKey('response_headers_hsts');
});

it('serializes nested hsts data with snake_case keys', function () {
    $data = new UpdateEnvironmentData(
        responseHeadersHsts: new HstsData(maxAge: 31536000, includeSubdomains: true, preload: false),
    );

    $array = $data->toArray();

    expect($array['response_headers_hsts']['max_age'])->toBe(31536000);
    expect($array['response_headers_hsts']['include_subdomains'])->toBeTrue();
    expect($array['response_headers_hsts']['preload'])->toBeFalse();
});

it('serializes filesystem keys', function () {
    $data = new UpdateEnvironmentData(
        filesystemKeys: [
            new FilesystemKeyData(id: 'key-123', disk: 'media', isDefaultDisk: true),
        ],
    );

    $array = $data->toArray();

    expect($array['filesystem_keys'][0]['id'])->toBe('key-123');
    expect($array['filesystem_keys'][0]['disk'])->toBe('media');
    expect($array['filesystem_keys'][0]['is_default_disk'])->toBeTrue();
});

it('serializes php_version in API request format with patch suffix', function () {
    expect((new UpdateEnvironmentData(phpVersion: PhpVersion::V8_2))->toArray()['php_version'])->toBe('8.2:1');
    expect((new UpdateEnvironmentData(phpVersion: PhpVersion::V8_3))->toArray()['php_version'])->toBe('8.3:1');
    expect((new UpdateEnvironmentData(phpVersion: PhpVersion::V8_4))->toArray()['php_version'])->toBe('8.4:1');
    expect((new UpdateEnvironmentData(phpVersion: PhpVersion::V8_5))->toArray()['php_version'])->toBe('8.5:1');
});
