<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentLogEntryData;
use Redberry\LaravelCloudSdk\Requests\Environments\GetEnvironmentLogsRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;
use Saloon\Laravel\Facades\Saloon;

function makeLogEntry(string $message, string $level = 'info', string $type = 'access'): array
{
    return [
        'message' => $message,
        'level' => $level,
        'type' => $type,
        'logged_at' => '2026-04-09T12:00:00Z',
        'data' => null,
    ];
}

function makeCursorResponse(array $entries, string $cursor = ''): MockResponse
{
    return MockResponse::make([
        'data' => $entries,
        'meta' => [
            'cursor' => $cursor,
            'type' => 'all',
            'from' => '2026-04-08T12:00:00Z',
            'to' => '2026-04-09T12:00:00Z',
        ],
    ]);
}

it('iterates through multiple pages using cursor', function () {
    Saloon::fake([
        makeCursorResponse([
            makeLogEntry('Log entry 1'),
            makeLogEntry('Log entry 2', 'error', 'application'),
        ], cursor: 'abc123'),
        makeCursorResponse([
            makeLogEntry('Log entry 3', 'warning', 'system'),
        ]),
    ]);

    $connector = new LaravelCloudConnector('test-token');
    $request = new GetEnvironmentLogsRequest('env-123', '2026-04-08T12:00:00Z', '2026-04-09T12:00:00Z');

    $items = $request->paginate($connector)->collect()->all();

    expect($items)->toHaveCount(3);
    expect($items[0])->toBeInstanceOf(EnvironmentLogEntryData::class);
    expect($items[0]->message)->toBe('Log entry 1');
    expect($items[2]->message)->toBe('Log entry 3');

    Saloon::assertSentCount(2);
});

it('stops on first page when cursor is empty', function () {
    Saloon::fake([
        makeCursorResponse([makeLogEntry('Only entry')]),
    ]);

    $connector = new LaravelCloudConnector('test-token');
    $request = new GetEnvironmentLogsRequest('env-123', '2026-04-08T12:00:00Z', '2026-04-09T12:00:00Z');

    $items = $request->paginate($connector)->collect()->all();

    expect($items)->toHaveCount(1);
    expect($items[0]->message)->toBe('Only entry');

    Saloon::assertSentCount(1);
});

it('stops on first page when cursor is null', function () {
    Saloon::fake([
        MockResponse::make([
            'data' => [],
            'meta' => ['cursor' => null, 'type' => 'all', 'from' => '2026-04-08T12:00:00Z', 'to' => '2026-04-09T12:00:00Z'],
        ]),
    ]);

    $connector = new LaravelCloudConnector('test-token');
    $request = new GetEnvironmentLogsRequest('env-123', '2026-04-08T12:00:00Z', '2026-04-09T12:00:00Z');

    $items = $request->paginate($connector)->collect()->all();

    expect($items)->toHaveCount(0);

    Saloon::assertSentCount(1);
});

it('passes cursor query parameter to subsequent requests', function () {
    Saloon::fake([
        makeCursorResponse([makeLogEntry('First page')], cursor: 'next-page-cursor'),
        makeCursorResponse([makeLogEntry('Second page')]),
    ]);

    $connector = new LaravelCloudConnector('test-token');
    $request = new GetEnvironmentLogsRequest('env-123', '2026-04-08T12:00:00Z', '2026-04-09T12:00:00Z');

    $request->paginate($connector)->collect()->toArray();

    Saloon::assertSent(function (Request $request) {
        if (! $request instanceof GetEnvironmentLogsRequest) {
            return false;
        }

        return $request->query()->get('cursor') === 'next-page-cursor';
    });
});
