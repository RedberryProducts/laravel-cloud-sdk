<?php

namespace Redberry\LaravelCloudSdk\Requests\WebsocketApplications;

use Redberry\LaravelCloudSdk\Data\WebsocketApplications\WebsocketApplicationData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetWebsocketApplicationRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $applicationId) {}

    public function resolveEndpoint(): string
    {
        return "/websocket-applications/{$this->applicationId}";
    }

    protected function defaultQuery(): array
    {
        return ['include' => 'server'];
    }

    public function createDtoFromResponse(Response $response): WebsocketApplicationData
    {
        return JsonApiHydrator::hydrateOne(
            WebsocketApplicationData::class,
            $response->json('data'),
            $response->json('included') ?? [],
        );
    }
}
