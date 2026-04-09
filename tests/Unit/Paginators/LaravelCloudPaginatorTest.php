<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentData;
use Redberry\LaravelCloudSdk\Requests\Environments\ListEnvironmentsRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;
use Saloon\Laravel\Facades\Saloon;

function makeEnvironmentItem(string $id, string $name): array
{
    return [
        'id' => $id,
        'type' => 'environments',
        'attributes' => [
            'name' => $name,
            'slug' => $name,
            'status' => 'running',
            'php_major_version' => '8.4',
            'node_version' => '22',
            'vanity_domain' => "https://$name.example.com",
            'created_from_automation' => false,
            'uses_octane' => false,
            'uses_hibernation' => false,
            'uses_push_to_deploy' => true,
            'uses_deploy_hook' => false,
            'build_command' => null,
            'deploy_command' => null,
            'environment_variables' => [],
            'network_settings' => [
                'cache' => ['strategy' => 'static'],
                'response_headers' => [
                    'frame' => 'sameorigin',
                    'content_type' => 'nosniff',
                    'robots_tag' => 'none',
                    'hsts' => [
                        'enabled' => false,
                        'max_age' => 0,
                        'include_subdomains' => false,
                        'preload' => false,
                    ],
                ],
                'firewall' => [
                    'rate_limit' => ['level' => null],
                    'under_attack_mode_started_at' => null,
                ],
            ],
            'created_at' => '2026-04-09T12:00:00Z',
        ],
    ];
}

function makePagedResponse(array $items, int $currentPage, int $lastPage): MockResponse
{
    return MockResponse::make([
        'data' => $items,
        'meta' => [
            'current_page' => $currentPage,
            'last_page' => $lastPage,
            'from' => 1,
            'to' => count($items),
            'total' => count($items) * $lastPage,
            'per_page' => count($items),
        ],
    ]);
}

it('iterates through multiple pages', function () {
    Saloon::fake([
        makePagedResponse([
            makeEnvironmentItem('env-1', 'production'),
            makeEnvironmentItem('env-2', 'staging'),
        ], currentPage: 1, lastPage: 2),
        makePagedResponse([
            makeEnvironmentItem('env-3', 'development'),
        ], currentPage: 2, lastPage: 2),
    ]);

    $connector = new LaravelCloudConnector('test-token');
    $items = $connector->paginate(new ListEnvironmentsRequest('app-123'))->collect()->all();

    expect($items)->toHaveCount(3);
    expect($items[0])->toBeInstanceOf(EnvironmentData::class);
    expect($items[0]->name)->toBe('production');
    expect($items[2]->name)->toBe('development');

    Saloon::assertSentCount(2);
});

it('stops on single page when current equals last', function () {
    Saloon::fake([
        makePagedResponse([
            makeEnvironmentItem('env-1', 'production'),
        ], currentPage: 1, lastPage: 1),
    ]);

    $connector = new LaravelCloudConnector('test-token');
    $items = $connector->paginate(new ListEnvironmentsRequest('app-123'))->collect()->all();

    expect($items)->toHaveCount(1);
    expect($items[0]->name)->toBe('production');

    Saloon::assertSentCount(1);
});

it('handles empty first page', function () {
    Saloon::fake([
        makePagedResponse([], currentPage: 1, lastPage: 1),
    ]);

    $connector = new LaravelCloudConnector('test-token');
    $items = $connector->paginate(new ListEnvironmentsRequest('app-123'))->collect()->all();

    expect($items)->toHaveCount(0);

    Saloon::assertSentCount(1);
});

it('increments page query parameter on subsequent requests', function () {
    Saloon::fake([
        makePagedResponse([
            makeEnvironmentItem('env-1', 'production'),
        ], currentPage: 1, lastPage: 2),
        makePagedResponse([
            makeEnvironmentItem('env-2', 'staging'),
        ], currentPage: 2, lastPage: 2),
    ]);

    $connector = new LaravelCloudConnector('test-token');
    $connector->paginate(new ListEnvironmentsRequest('app-123'))->collect()->toArray();

    Saloon::assertSent(function (Request $request) {
        if (! $request instanceof ListEnvironmentsRequest) {
            return false;
        }

        return $request->query()->get('page') === 2;
    });
});
