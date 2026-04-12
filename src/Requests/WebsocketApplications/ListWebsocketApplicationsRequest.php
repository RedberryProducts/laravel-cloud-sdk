<?php

namespace Redberry\LaravelCloudSdk\Requests\WebsocketApplications;

use Redberry\LaravelCloudSdk\Data\WebsocketApplications\WebsocketApplicationData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class ListWebsocketApplicationsRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(private string $clusterId) {}

    public function resolveEndpoint(): string
    {
        return "/websocket-servers/{$this->clusterId}/applications";
    }

    protected function defaultQuery(): array
    {
        return ['include' => 'server'];
    }

    /**
     * @return WebsocketApplicationData[]
     */
    public function createDtoFromResponse(Response $response): array
    {
        return JsonApiHydrator::hydrateMany(
            WebsocketApplicationData::class,
            $response->json('data'),
            $response->json('included') ?? [],
        );
    }
}
