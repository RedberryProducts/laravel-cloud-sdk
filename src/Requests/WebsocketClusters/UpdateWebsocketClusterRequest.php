<?php

namespace Redberry\LaravelCloudSdk\Requests\WebsocketClusters;

use Redberry\LaravelCloudSdk\Data\WebsocketClusters\UpdateWebsocketClusterData;
use Redberry\LaravelCloudSdk\Data\WebsocketClusters\WebsocketClusterData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateWebsocketClusterRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    public function __construct(
        private string $clusterId,
        private UpdateWebsocketClusterData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/websocket-servers/{$this->clusterId}";
    }

    protected function defaultBody(): array
    {
        return $this->data->toArray();
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
