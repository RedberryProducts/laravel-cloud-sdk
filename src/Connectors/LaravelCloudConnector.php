<?php

namespace Redberry\LaravelCloudSdk\Connectors;

use Redberry\LaravelCloudSdk\Connectors\Auth\LaravelCloudTokenAuthenticator;
use Redberry\LaravelCloudSdk\Exceptions\AuthenticationException;
use Redberry\LaravelCloudSdk\Exceptions\CloudException;
use Redberry\LaravelCloudSdk\Exceptions\NotFoundException;
use Redberry\LaravelCloudSdk\Exceptions\RateLimitException;
use Redberry\LaravelCloudSdk\Exceptions\ValidationException;
use Saloon\Contracts\Authenticator;
use Saloon\Http\Connector;
use Saloon\Http\Response;
use Saloon\Http\Senders\GuzzleSender;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

class LaravelCloudConnector extends Connector
{
    use AcceptsJson, AlwaysThrowOnErrors;

    protected string $defaultSender = GuzzleSender::class;

    public function __construct(private string $token) {}

    public function resolveBaseUrl(): string
    {
        return 'https://cloud.laravel.com/api';
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }

    protected function defaultConfig(): array
    {
        return [];
    }

    protected function defaultAuth(): Authenticator
    {
        return new LaravelCloudTokenAuthenticator($this->token);
    }

    public function getRequestException(Response $response, ?\Throwable $senderException): ?\Throwable
    {
        return match ($response->status()) {
            401 => new AuthenticationException($response, $senderException),
            404 => new NotFoundException($response, $senderException),
            422 => new ValidationException($response, $senderException),
            429 => new RateLimitException($response, $senderException),
            default => new CloudException($response, $senderException),
        };
    }
}
