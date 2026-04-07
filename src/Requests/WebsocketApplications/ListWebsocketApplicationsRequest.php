<?php

namespace Redberry\LaravelCloudSdk\Requests\WebsocketApplications;

use Redberry\LaravelCloudSdk\Data\WebsocketApplications\WebsocketApplicationData;
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

    /**
     * @return WebsocketApplicationData[]
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            fn (array $item) => WebsocketApplicationData::fromResponse($item['attributes'], $item['id']),
            $response->json('data')
        );
    }
}
