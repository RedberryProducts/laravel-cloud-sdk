<?php

namespace Redberry\LaravelCloudSdk\Connectors;

use Redberry\LaravelCloudSdk\Connectors\Auth\LaravelCloudTokenAuthenticator;
use Redberry\LaravelCloudSdk\Exceptions\HtmlResponseException;
use Redberry\LaravelCloudSdk\Exceptions\RateLimitException;
use Redberry\LaravelCloudSdk\Exceptions\ValidationException;
use Redberry\LaravelCloudSdk\Paginators\LaravelCloudPaginator;
use Redberry\LaravelCloudSdk\Traits\Plugins\DetectsHtmlResponses;
use Saloon\Contracts\Authenticator;
use Saloon\Http\Connector;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Http\Senders\GuzzleSender;
use Saloon\PaginationPlugin\Contracts\HasPagination;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

class LaravelCloudConnector extends Connector implements HasPagination
{
    use AcceptsJson, AlwaysThrowOnErrors, DetectsHtmlResponses;

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

    public function paginate(Request $request): LaravelCloudPaginator
    {
        return new LaravelCloudPaginator(connector: $this, request: $request);
    }

    public function getRequestException(Response $response, ?\Throwable $senderException): ?\Throwable
    {
        if ($this->isHtmlResponse($response)) {
            return new HtmlResponseException($response);
        }

        return match ($response->status()) {
            422 => new ValidationException($response, $senderException),
            429 => new RateLimitException($response, $senderException),
            default => null,
        };
    }
}
