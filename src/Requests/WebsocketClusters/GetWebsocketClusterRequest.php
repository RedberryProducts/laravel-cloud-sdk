<?php

namespace Redberry\LaravelCloudSdk\Requests\WebsocketClusters;

use Redberry\LaravelCloudSdk\Data\WebsocketClusters\WebsocketClusterData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetWebsocketClusterRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $clusterId) {}

    public function resolveEndpoint(): string
    {
        return "/websocket-servers/{$this->clusterId}";
    }

    protected function defaultQuery(): array
    {
        return ['include' => 'applications'];
    }

    public function createDtoFromResponse(Response $response): WebsocketClusterData
    {
        return JsonApiHydrator::hydrateOne(
            WebsocketClusterData::class,
            $response->json('data'),
            $response->json('included') ?? [],
        );
    }
}
