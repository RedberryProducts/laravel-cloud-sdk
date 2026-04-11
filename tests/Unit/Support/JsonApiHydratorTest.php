<?php

use Redberry\LaravelCloudSdk\Data\Applications\ApplicationData;
use Redberry\LaravelCloudSdk\Data\Buckets\BucketData;
use Redberry\LaravelCloudSdk\Data\Buckets\BucketKeyData;
use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentData;
use Redberry\LaravelCloudSdk\Data\Meta\OrganizationData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;

it('hydrates a singular relationship', function () {
    $data = [
        'id' => 'app_1',
        'type' => 'applications',
        'attributes' => applicationAttributes(),
        'relationships' => [
            'organization' => [
                'data' => ['type' => 'organizations', 'id' => 'org_1'],
            ],
        ],
    ];

    $included = [
        ['id' => 'org_1', 'type' => 'organizations', 'attributes' => ['name' => 'Acme', 'slug' => 'acme']],
    ];

    $result = JsonApiHydrator::hydrateOne(ApplicationData::class, $data, $included);

    expect($result)
        ->toBeInstanceOf(ApplicationData::class)
        ->organization->toBeInstanceOf(OrganizationData::class)
        ->organization->id->toBe('org_1')
        ->organization->name->toBe('Acme');
});

it('hydrates an array relationship', function () {
    $data = [
        'id' => 'app_1',
        'type' => 'applications',
        'attributes' => applicationAttributes(),
        'relationships' => [
            'environments' => [
                'data' => [
                    ['type' => 'environments', 'id' => 'env_1'],
                    ['type' => 'environments', 'id' => 'env_2'],
                ],
            ],
        ],
    ];

    $included = [
        ['id' => 'env_1', 'type' => 'environments', 'attributes' => environmentAttributes(['name' => 'Production'])],
        ['id' => 'env_2', 'type' => 'environments', 'attributes' => environmentAttributes(['name' => 'Staging'])],
    ];

    $result = JsonApiHydrator::hydrateOne(ApplicationData::class, $data, $included);

    expect($result)
        ->toBeInstanceOf(ApplicationData::class)
        ->environments->toHaveCount(2)
        ->and($result->environments[0])->toBeInstanceOf(EnvironmentData::class)->name->toBe('Production')
        ->and($result->environments[1])->toBeInstanceOf(EnvironmentData::class)->name->toBe('Staging');
});

it('keeps null default for missing singular relationship', function () {
    $data = [
        'id' => 'app_1',
        'type' => 'applications',
        'attributes' => applicationAttributes(),
    ];

    $result = JsonApiHydrator::hydrateOne(ApplicationData::class, $data);

    expect($result)
        ->toBeInstanceOf(ApplicationData::class)
        ->organization->toBeNull();
});

it('keeps empty array default for missing array relationship', function () {
    $data = [
        'id' => 'app_1',
        'type' => 'applications',
        'attributes' => applicationAttributes(),
    ];

    $result = JsonApiHydrator::hydrateOne(ApplicationData::class, $data);

    expect($result)
        ->toBeInstanceOf(ApplicationData::class)
        ->environments->toBe([]);
});

it('handles null relationship data', function () {
    $data = [
        'id' => 'app_1',
        'type' => 'applications',
        'attributes' => applicationAttributes(),
        'relationships' => [
            'organization' => ['data' => null],
        ],
    ];

    $result = JsonApiHydrator::hydrateOne(ApplicationData::class, $data);

    expect($result)
        ->toBeInstanceOf(ApplicationData::class)
        ->organization->toBeNull();
});

it('handles empty array relationship data', function () {
    $data = [
        'id' => 'app_1',
        'type' => 'applications',
        'attributes' => applicationAttributes(),
        'relationships' => [
            'environments' => ['data' => []],
        ],
    ];

    $result = JsonApiHydrator::hydrateOne(ApplicationData::class, $data);

    expect($result)
        ->toBeInstanceOf(ApplicationData::class)
        ->environments->toBe([]);
});

it('handles renamed properties', function () {
    $data = [
        'id' => 'key_1',
        'type' => 'filesystemKeys',
        'attributes' => [
            'name' => 'my-key',
            'permission' => 'read-write',
            'access_key_id' => 'ak_123',
            'access_key_secret' => 'sk_123',
            'created_at' => '2025-01-01T00:00:00Z',
        ],
        'relationships' => [
            'filesystem' => [
                'data' => ['type' => 'filesystems', 'id' => 'bucket_1'],
            ],
        ],
    ];

    $included = [
        [
            'id' => 'bucket_1',
            'type' => 'filesystems',
            'attributes' => [
                'name' => 'my-bucket',
                'type' => 'general',
                'status' => 'active',
                'visibility' => 'private',
                'jurisdiction' => 'default',
                'endpoint' => 'https://example.com',
                'url' => 'https://example.com',
                'allowed_origins' => null,
                'created_at' => '2025-01-01T00:00:00Z',
            ],
        ],
    ];

    $result = JsonApiHydrator::hydrateOne(BucketKeyData::class, $data, $included);

    expect($result)
        ->toBeInstanceOf(BucketKeyData::class)
        ->bucket->toBeInstanceOf(BucketData::class)
        ->bucket->id->toBe('bucket_1')
        ->bucket->name->toBe('my-bucket');
});

it('skips relationships with no matching DTO property', function () {
    $data = [
        'id' => 'app_1',
        'type' => 'applications',
        'attributes' => applicationAttributes(),
        'relationships' => [
            'nonExistent' => [
                'data' => ['type' => 'things', 'id' => '1'],
            ],
        ],
    ];

    $result = JsonApiHydrator::hydrateOne(ApplicationData::class, $data);

    expect($result)->toBeInstanceOf(ApplicationData::class)->id->toBe('app_1');
});

it('hydrates many with shared included map', function () {
    $items = [
        [
            'id' => 'app_1',
            'type' => 'applications',
            'attributes' => applicationAttributes(['name' => 'First']),
            'relationships' => [
                'organization' => ['data' => ['type' => 'organizations', 'id' => 'org_1']],
            ],
        ],
        [
            'id' => 'app_2',
            'type' => 'applications',
            'attributes' => applicationAttributes(['name' => 'Second']),
            'relationships' => [
                'organization' => ['data' => ['type' => 'organizations', 'id' => 'org_1']],
            ],
        ],
    ];

    $included = [
        ['id' => 'org_1', 'type' => 'organizations', 'attributes' => ['name' => 'Acme', 'slug' => 'acme']],
    ];

    $results = JsonApiHydrator::hydrateMany(ApplicationData::class, $items, $included);

    expect($results)->toHaveCount(2)
        ->and($results[0])->toBeInstanceOf(ApplicationData::class)->name->toBe('First')
        ->and($results[0]->organization)->toBeInstanceOf(OrganizationData::class)->id->toBe('org_1')
        ->and($results[1])->toBeInstanceOf(ApplicationData::class)->name->toBe('Second')
        ->and($results[1]->organization)->toBeInstanceOf(OrganizationData::class)->id->toBe('org_1');
});

function applicationAttributes(array $overrides = []): array
{
    return array_merge([
        'name' => 'My App',
        'slug' => 'my-app',
        'region' => 'us-east-1',
        'slack_channel' => null,
        'avatar_url' => null,
        'repository' => null,
        'created_at' => null,
    ], $overrides);
}

function environmentAttributes(array $overrides = []): array
{
    return array_merge([
        'name' => 'Production',
        'slug' => 'production',
        'status' => 'running',
        'php_major_version' => '8.4',
        'node_version' => '22',
        'vanity_domain' => 'app.laravel.cloud',
        'created_from_automation' => false,
        'uses_octane' => false,
        'uses_hibernation' => false,
        'uses_push_to_deploy' => true,
        'uses_deploy_hook' => false,
        'build_command' => null,
        'deploy_command' => null,
        'environment_variables' => [],
        'network_settings' => [
            'cache' => ['strategy' => 'standard'],
            'response_headers' => [
                'frame' => 'deny',
                'content_type' => 'nosniff',
                'robots_tag' => 'none',
                'hsts' => [
                    'max_age' => null,
                    'include_subdomains' => false,
                    'preload' => false,
                ],
            ],
            'firewall' => [
                'rate_limit' => ['level' => null],
            ],
        ],
        'created_at' => null,
    ], $overrides);
}
