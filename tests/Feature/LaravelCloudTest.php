<?php

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Exceptions\HtmlResponseException;
use Redberry\LaravelCloudSdk\Exceptions\RateLimitException;
use Redberry\LaravelCloudSdk\Exceptions\ValidationException;
use Redberry\LaravelCloudSdk\Facades\LaravelCloud as LaravelCloudFacade;
use Redberry\LaravelCloudSdk\LaravelCloud;
use Redberry\LaravelCloudSdk\Requests\Applications\CreateApplicationRequest;
use Redberry\LaravelCloudSdk\Requests\Applications\GetApplicationRequest;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Exceptions\Request\Statuses\UnauthorizedException;
use Saloon\Exceptions\Request\Statuses\NotFoundException;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;

// --- Construction ---

it('can be instantiated with a token', function () {
    $cloud = new LaravelCloud('my-token');

    expect($cloud)->toBeInstanceOf(LaravelCloud::class);
});

// --- Container / Service Provider ---

it('can be resolved from the container when a token is configured', function () {
    config(['laravel-cloud-sdk.token' => 'test-token']);

    $cloud = app(LaravelCloud::class);

    expect($cloud)->toBeInstanceOf(LaravelCloud::class);
});

it('creates a new instance on each container resolution', function () {
    config(['laravel-cloud-sdk.token' => 'test-token']);

    $first = app(LaravelCloud::class);
    $second = app(LaravelCloud::class);

    expect($first)->not->toBe($second);
});

it('throws a RuntimeException when resolved from container without a configured token', function () {
    config(['laravel-cloud-sdk.token' => null]);

    app(LaravelCloud::class);
})->throws(RuntimeException::class, 'Laravel Cloud token is not configured');

// --- Facade forToken ---

it('facade forToken() returns a new LaravelCloud instance without touching the container', function () {
    config(['laravel-cloud-sdk.token' => null]);

    $cloud = LaravelCloudFacade::forToken('dynamic-token');

    expect($cloud)->toBeInstanceOf(LaravelCloud::class);
});

// --- Facade ---

it('facade proxies method calls to a LaravelCloud instance', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
    ]);

    $result = LaravelCloudFacade::applications();

    expect($result)->toBeInstanceOf(Collection::class);
});

// --- Exception mapping ---

it('throws UnauthorizedException on 401', function () {
    Saloon::fake([
        ListApplicationsRequest::class => MockResponse::make([], 401),
    ]);

    (new LaravelCloud('bad-token'))->applications();
})->throws(UnauthorizedException::class);

it('throws NotFoundException on 404', function () {
    Saloon::fake([
        GetApplicationRequest::class => MockResponse::make([], 404),
    ]);

    (new LaravelCloud('token'))->application('missing-id');
})->throws(NotFoundException::class);

it('throws ValidationException on 422 and exposes errors()', function () {
    Saloon::fake([
        CreateApplicationRequest::class => MockResponse::make([
            'message' => 'The given data was invalid.',
            'errors' => ['name' => ['The name field is required.']],
        ], 422),
    ]);

    try {
        (new LaravelCloud('token'))->createApplication(
            repository: 'org/repo',
            name: 'app',
            region: 'us-east-1',
            sourceControlProviderType: 'github',
        );
    } catch (ValidationException $e) {
        expect($e->errors())->toHaveKey('name');
    }
});

it('throws HtmlResponseException when API returns an HTML response', function () {
    Saloon::fake([
        ListApplicationsRequest::class => MockResponse::make('<!DOCTYPE html><html></html>', 200, ['Content-Type' => 'text/html; charset=utf-8']),
    ]);

    (new LaravelCloud('token'))->applications();
})->throws(HtmlResponseException::class);

it('throws RateLimitException on 429 and exposes retryAfter()', function () {
    Saloon::fake([
        ListApplicationsRequest::class => MockResponse::make([], 429, ['Retry-After' => '60']),
    ]);

    try {
        (new LaravelCloud('token'))->applications();
    } catch (RateLimitException $e) {
        expect($e->retryAfter())->toBe(60);
    }
});

// --- Enum resilience on responses ---

it('returns raw string for unknown enum values on response DTOs', function () {
    Saloon::fake([
        ListApplicationsRequest::class => MockResponse::make([
            'data' => [[
                'id' => 'app-123',
                'type' => 'applications',
                'attributes' => [
                    'name' => 'test-app',
                    'slug' => 'test-app',
                    'region' => 'ap-southeast-99',
                    'slack_channel' => null,
                    'avatar_url' => null,
                    'created_at' => '2025-01-01T00:00:00Z',
                ],
            ]],
        ], 200),
    ]);

    $application = (new LaravelCloud('token'))->applications()->first();

    expect($application->region)->toBe('ap-southeast-99');
});
