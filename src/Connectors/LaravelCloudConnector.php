<?php

namespace App\Http\Integrations\LaravelCloud;

use App\Http\Integrations\LaravelCloud\Auth\LaravelCloudTokenAuthenticator;
use Saloon\Contracts\Authenticator;
use Saloon\Http\Connector;
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
}
