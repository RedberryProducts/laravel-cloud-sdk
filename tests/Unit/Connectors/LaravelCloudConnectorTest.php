<?php

use App\Http\Integrations\LaravelCloud\Auth\LaravelCloudTokenAuthenticator;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\Meta\ListRegionsRequest;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;

it('correctly sets the base url', function () {
    $connector = new LaravelCloudConnector('test-token');

    expect($connector->resolveBaseUrl())->toBe('https://cloud.laravel.com/api');
});

it('uses LaravelCloudTokenAuthenticator as default auth', function () {
    $connector = new LaravelCloudConnector('test-token');
    $authenticator = $connector->getAuthenticator();

    expect($authenticator)->toBeInstanceOf(LaravelCloudTokenAuthenticator::class);
});

it('can send authenticated requests', function () {
    Saloon::fake([
        ListRegionsRequest::class => MockResponse::make(['data' => []], 200),
    ]);

    $connector = new LaravelCloudConnector('test-token');
    $response = $connector->send(new ListRegionsRequest);

    Saloon::assertSent(ListRegionsRequest::class);
    expect($response->status())->toBe(200);
});

it('throws an exception if the response is not successful', function () {
    Saloon::fake([
        ListRegionsRequest::class => MockResponse::make([], 401),
    ]);

    $connector = new LaravelCloudConnector('invalid-token');
    $connector->send(new ListRegionsRequest);
})->throws(RequestException::class);

it('sends accept json header', function () {
    Saloon::fake([
        ListRegionsRequest::class => MockResponse::make([], 200),
    ]);

    $connector = new LaravelCloudConnector('test-token');
    $response = $connector->send(new ListRegionsRequest);

    $headers = $response->getPendingRequest()->headers()->all();
    expect($headers['Accept'])->toBe('application/json');
});

it('sends content-type json header', function () {
    Saloon::fake([
        ListRegionsRequest::class => MockResponse::make([], 200),
    ]);

    $connector = new LaravelCloudConnector('test-token');
    $response = $connector->send(new ListRegionsRequest);

    $headers = $response->getPendingRequest()->headers()->all();
    expect($headers['Content-Type'])->toBe('application/json');
});
