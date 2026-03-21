<?php

use App\Data\LaravelCloud\Environments\EnvironmentData;
use App\Data\LaravelCloud\Environments\EnvironmentVariableData;
use App\Data\LaravelCloud\Environments\FilesystemKeyData;
use App\Data\LaravelCloud\Environments\HstsData;
use App\Data\LaravelCloud\Environments\NetworkSettingsData;
use App\Data\LaravelCloud\Environments\UpdateEnvironmentData;
use App\Enums\LaravelCloud\CacheStrategy;
use App\Enums\LaravelCloud\EnvironmentColor;
use App\Enums\LaravelCloud\EnvironmentStatus;
use App\Enums\LaravelCloud\FirewallRateLimitLevel;
use App\Enums\LaravelCloud\NodeVersion;
use App\Enums\LaravelCloud\PhpVersion;
use App\Enums\LaravelCloud\ResponseHeadersContentType;
use App\Enums\LaravelCloud\ResponseHeadersFrame;
use App\Enums\LaravelCloud\ResponseHeadersRobotsTag;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\Applications\ListApplicationsRequest;
use App\Http\Integrations\LaravelCloud\Requests\Environments\ListEnvironmentsRequest;
use App\Http\Integrations\LaravelCloud\Requests\Environments\UpdateEnvironmentRequest;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $data = new UpdateEnvironmentData(name: 'updated-env');
    $request = new UpdateEnvironmentRequest('env-123', $data);

    expect($request->resolveEndpoint())->toBe('/environments/env-123');
});

it('has the correct HTTP method', function () {
    $data = new UpdateEnvironmentData(name: 'updated-env');
    $request = new UpdateEnvironmentRequest('env-123', $data);

    expect($request->getMethod())->toBe(Method::PATCH);
});

it('sends correct body with all optional fields', function () {
    $data = new UpdateEnvironmentData(
        name: 'staging',
        slug: 'staging-env',
        color: EnvironmentColor::Blue,
        branch: 'develop',
        phpVersion: PhpVersion::V8_4,
        nodeVersion: NodeVersion::V22,
        buildCommand: 'npm run build',
        deployCommand: 'php artisan migrate --force',
        usesPushToDeploy: true,
        usesDeployHook: false,
        usesOctane: false,
        usesVanityDomain: true,
        timeout: 60,
        sleepTimeout: 300,
        shutdownTimeout: 30,
        usesPurgeEdgeCacheOnDeploy: true,
        nightwatchToken: null,
        cacheStrategy: CacheStrategy::Bypass,
        responseHeadersFrame: ResponseHeadersFrame::Deny,
        responseHeadersContentType: ResponseHeadersContentType::Nosniff,
        responseHeadersRobotsTag: ResponseHeadersRobotsTag::IndexFollow,
        responseHeadersHsts: new HstsData(maxAge: 31536000, includeSubdomains: true, preload: false),
        filesystemKeys: [new FilesystemKeyData(id: 'key-123', disk: 'media', isDefaultDisk: true)],
        firewallRateLimitLevel: FirewallRateLimitLevel::Throttle,
        firewallUnderAttackMode: false,
        databaseSchemaId: 'schema-123',
        cacheId: 'cache-123',
        websocketApplicationId: 'wsa-123',
    );
    $request = new UpdateEnvironmentRequest('env-123', $data);
    $body = $request->body()->all();

    expect($body['name'])->toBe('staging');
    expect($body['slug'])->toBe('staging-env');
    expect($body['color'])->toBe('blue');
    expect($body['branch'])->toBe('develop');
    expect($body['php_version'])->toBe('8.4:1');
    expect($body['node_version'])->toBe('22');
    expect($body['build_command'])->toBe('npm run build');
    expect($body['deploy_command'])->toBe('php artisan migrate --force');
    expect($body['uses_push_to_deploy'])->toBeTrue();
    expect($body['uses_deploy_hook'])->toBeFalse();
    expect($body['uses_octane'])->toBeFalse();
    expect($body['uses_vanity_domain'])->toBeTrue();
    expect($body['timeout'])->toBe(60);
    expect($body['sleep_timeout'])->toBe(300);
    expect($body['shutdown_timeout'])->toBe(30);
    expect($body['uses_purge_edge_cache_on_deploy'])->toBeTrue();
    expect($body['nightwatch_token'])->toBeNull();
    expect($body['cache_strategy'])->toBe('bypass');
    expect($body['response_headers_frame'])->toBe('deny');
    expect($body['response_headers_content_type'])->toBe('nosniff');
    expect($body['response_headers_robots_tag'])->toBe('index, follow');
    expect($body['response_headers_hsts']['max_age'])->toBe(31536000);
    expect($body['response_headers_hsts']['include_subdomains'])->toBeTrue();
    expect($body['response_headers_hsts']['preload'])->toBeFalse();
    expect($body['filesystem_keys'][0]['id'])->toBe('key-123');
    expect($body['filesystem_keys'][0]['disk'])->toBe('media');
    expect($body['filesystem_keys'][0]['is_default_disk'])->toBeTrue();
    expect($body['firewall_rate_limit_level'])->toBe('throttle');
    expect($body['firewall_under_attack_mode'])->toBeFalse();
    expect($body['database_schema_id'])->toBe('schema-123');
    expect($body['cache_id'])->toBe('cache-123');
    expect($body['websocket_application_id'])->toBe('wsa-123');
});

it('excludes unset optional fields from body', function () {
    $data = new UpdateEnvironmentData(name: 'updated-env');
    $request = new UpdateEnvironmentRequest('env-123', $data);
    $body = $request->body()->all();

    expect($body)->toHaveKey('name');
    expect($body)->not->toHaveKey('slug');
    expect($body)->not->toHaveKey('color');
    expect($body)->not->toHaveKey('branch');
    expect($body)->not->toHaveKey('php_version');
    expect($body)->not->toHaveKey('node_version');
    expect($body)->not->toHaveKey('build_command');
    expect($body)->not->toHaveKey('deploy_command');
    expect($body)->not->toHaveKey('nightwatch_token');
    expect($body)->not->toHaveKey('cache_strategy');
    expect($body)->not->toHaveKey('response_headers_hsts');
    expect($body)->not->toHaveKey('filesystem_keys');
    expect($body)->not->toHaveKey('database_schema_id');
    expect($body)->not->toHaveKey('cache_id');
    expect($body)->not->toHaveKey('websocket_application_id');
});

it('updates an environment and returns EnvironmentData with all fields', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud.token'));
    $firstApplication = $connector->send(new ListApplicationsRequest)->dtoOrFail()->first();

    Saloon::fake([
        ListEnvironmentsRequest::class => new LaravelCloudFixture('environments/list'),
    ]);

    $firstEnvironment = $connector->send(new ListEnvironmentsRequest($firstApplication->id))->dtoOrFail()->first();

    Saloon::fake([
        UpdateEnvironmentRequest::class => new LaravelCloudFixture('environments/update'),
    ]);

    $data = new UpdateEnvironmentData(name: 'updated-env');
    $response = $connector->send(new UpdateEnvironmentRequest($firstEnvironment->id, $data));

    Saloon::assertSent(UpdateEnvironmentRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(EnvironmentData::class);
    expect($dto->id)->toBe('env-a14fe550-4e39-4ff2-8016-a20e4d32a996');
    expect($dto->name)->toBe('updated-env');
    expect($dto->slug)->toBe('main');
    expect($dto->status)->toBe(EnvironmentStatus::RUNNING);
    expect($dto->phpMajorVersion)->toBe(PhpVersion::V8_4);
    expect($dto->nodeVersion)->toBe(NodeVersion::V24);
    expect($dto->vanityDomain)->toBe('updated-app-updated-env-mtkrkd.laravel.cloud');
    expect($dto->createdFromAutomation)->toBeFalse();
    expect($dto->usesOctane)->toBeFalse();
    expect($dto->usesHibernation)->toBeFalse();
    expect($dto->usesPushToDeploy)->toBeTrue();
    expect($dto->usesDeployHook)->toBeFalse();
    expect($dto->buildCommand)->toBeString();
    expect($dto->deployCommand)->toBeString();
    expect($dto->environmentVariables)->toBeArray();
    expect($dto->environmentVariables[0])->toBeInstanceOf(EnvironmentVariableData::class);
    expect($dto->environmentVariables[0]->key)->toBe('APP_KEY');
    expect($dto->networkSettings)->toBeInstanceOf(NetworkSettingsData::class);
    expect($dto->networkSettings->cacheStrategy)->toBe('default');
    expect($dto->networkSettings->responseHeadersFrame)->toBe('deny');
    expect($dto->networkSettings->responseHeadersContentType)->toBe('nosniff');
    expect($dto->networkSettings->responseHeadersRobotsTag)->toBe('index, follow');
    expect($dto->networkSettings->responseHeadersHsts)->toBeInstanceOf(HstsData::class);
    expect($dto->networkSettings->responseHeadersHsts->maxAge)->toBeNull();
    expect($dto->networkSettings->responseHeadersHsts->includeSubdomains)->toBeFalse();
    expect($dto->networkSettings->responseHeadersHsts->preload)->toBeFalse();
    expect($dto->networkSettings->firewallRateLimitLevel)->toBeNull();
    expect($dto->networkSettings->firewallUnderAttackMode)->toBeFalse();
    expect($dto->createdAt)->not->toBeNull();
});
