<?php

use App\Data\LaravelCloud\Environments\EnvironmentData;
use App\Data\LaravelCloud\Environments\EnvironmentVariableData;
use App\Data\LaravelCloud\Environments\NetworkSettingsData;
use App\Enums\LaravelCloud\EnvironmentStatus;
use App\Enums\LaravelCloud\NodeVersion;
use App\Enums\LaravelCloud\PhpVersion;
use Carbon\CarbonImmutable;

$baseAttributes = [
    'name' => 'production',
    'slug' => 'production',
    'status' => 'running',
    'php_major_version' => '8.4',
    'node_version' => '22',
    'vanity_domain' => 'production.laravel.cloud',
    'created_from_automation' => false,
    'uses_octane' => false,
    'uses_hibernation' => false,
    'uses_push_to_deploy' => true,
    'uses_deploy_hook' => false,
    'build_command' => 'npm run build',
    'deploy_command' => null,
    'environment_variables' => [
        ['key' => 'APP_NAME', 'value' => 'Laravel'],
    ],
    'network_settings' => [
        'cache' => ['strategy' => 'default'],
        'response_headers' => [
            'frame' => 'deny',
            'content_type' => 'nosniff',
            'robots_tag' => 'index, follow',
            'hsts' => ['max_age' => null, 'include_subdomains' => false, 'preload' => false],
        ],
        'firewall' => [
            'bot_categories' => [],
            'rate_limit' => ['level' => null, 'per_minute' => null, '4xx' => false, '429' => false],
            'under_attack_mode_started_at' => null,
        ],
        'content_converter' => false,
    ],
    'created_at' => '2024-06-15T10:30:00Z',
];

it('can be created from API response data', function () use ($baseAttributes) {
    $data = EnvironmentData::fromResponse($baseAttributes, 'env-123');

    expect($data)->toBeInstanceOf(EnvironmentData::class);
    expect($data->id)->toBe('env-123');
    expect($data->name)->toBe('production');
    expect($data->slug)->toBe('production');
    expect($data->status)->toBe(EnvironmentStatus::RUNNING);
    expect($data->phpMajorVersion)->toBe(PhpVersion::V8_4);
    expect($data->nodeVersion)->toBe(NodeVersion::V22);
    expect($data->vanityDomain)->toBe('production.laravel.cloud');
    expect($data->createdFromAutomation)->toBeFalse();
    expect($data->usesOctane)->toBeFalse();
    expect($data->usesHibernation)->toBeFalse();
    expect($data->usesPushToDeploy)->toBeTrue();
    expect($data->usesDeployHook)->toBeFalse();
    expect($data->buildCommand)->toBe('npm run build');
    expect($data->deployCommand)->toBeNull();
    expect($data->createdAt)->toBeInstanceOf(CarbonImmutable::class);
});

it('maps environment variables to EnvironmentVariableData', function () use ($baseAttributes) {
    $data = EnvironmentData::fromResponse($baseAttributes, 'env-123');

    expect($data->environmentVariables)->toHaveCount(1);
    expect($data->environmentVariables[0])->toBeInstanceOf(EnvironmentVariableData::class);
    expect($data->environmentVariables[0]->key)->toBe('APP_NAME');
    expect($data->environmentVariables[0]->value)->toBe('Laravel');
});

it('maps network settings to NetworkSettingsData', function () use ($baseAttributes) {
    $data = EnvironmentData::fromResponse($baseAttributes, 'env-123');

    expect($data->networkSettings)->toBeInstanceOf(NetworkSettingsData::class);
    expect($data->networkSettings->cacheStrategy)->toBe('default');
    expect($data->networkSettings->responseHeadersFrame)->toBe('deny');
    expect($data->networkSettings->firewallUnderAttackMode)->toBeFalse();
});

it('handles null created_at', function () use ($baseAttributes) {
    $data = EnvironmentData::fromResponse([...$baseAttributes, 'created_at' => null], 'env-456');

    expect($data->createdAt)->toBeNull();
});
