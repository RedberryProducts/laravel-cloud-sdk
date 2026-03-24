<?php

use App\Http\Integrations\LaravelCloud\Auth\LaravelCloudTokenAuthenticator;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\Meta\ListRegionsRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;

it('sets bearer authorization header', function () {
    Saloon::fake([
        ListRegionsRequest::class => MockResponse::make([], 200),
    ]);

    $connector = new LaravelCloudConnector('my-secret-token');
    $response = $connector->send(new ListRegionsRequest);

    $headers = $response->getPendingRequest()->headers()->all();
    expect($headers['Authorization'])->toBe('Bearer my-secret-token');
});

it('uses LaravelCloudTokenAuthenticator', function () {
    $connector = new LaravelCloudConnector('test-token');
    $authenticator = $connector->getAuthenticator();

    expect($authenticator)->toBeInstanceOf(LaravelCloudTokenAuthenticator::class);
});
