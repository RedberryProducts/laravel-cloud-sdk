<?php

use App\Data\LaravelCloud\Environments\HstsData;
use App\Data\LaravelCloud\Environments\NetworkSettingsData;

$baseAttributes = [
    'cache' => ['strategy' => 'default'],
    'response_headers' => [
        'frame' => 'deny',
        'content_type' => 'nosniff',
        'robots_tag' => 'index, follow',
        'hsts' => ['max_age' => 31536000, 'include_subdomains' => true, 'preload' => false],
    ],
    'firewall' => [
        'bot_categories' => [],
        'rate_limit' => ['level' => 'ban', 'per_minute' => null, '4xx' => false, '429' => false],
        'under_attack_mode_started_at' => null,
    ],
    'content_converter' => false,
];

it('can be created from API response data', function () use ($baseAttributes) {
    $data = NetworkSettingsData::fromResponse($baseAttributes);

    expect($data)->toBeInstanceOf(NetworkSettingsData::class);
    expect($data->cacheStrategy)->toBe('default');
    expect($data->responseHeadersFrame)->toBe('deny');
    expect($data->responseHeadersContentType)->toBe('nosniff');
    expect($data->responseHeadersRobotsTag)->toBe('index, follow');
    expect($data->firewallRateLimitLevel)->toBe('ban');
    expect($data->firewallUnderAttackMode)->toBeFalse();
});

it('maps hsts to HstsData', function () use ($baseAttributes) {
    $data = NetworkSettingsData::fromResponse($baseAttributes);

    expect($data->responseHeadersHsts)->toBeInstanceOf(HstsData::class);
    expect($data->responseHeadersHsts->maxAge)->toBe(31536000);
    expect($data->responseHeadersHsts->includeSubdomains)->toBeTrue();
    expect($data->responseHeadersHsts->preload)->toBeFalse();
});
