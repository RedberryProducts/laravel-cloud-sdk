<?php

namespace Redberry\LaravelCloudSdk\Requests\WebsocketClusters;

use Redberry\LaravelCloudSdk\Data\WebsocketClusters\CreateWebsocketClusterData;
use Redberry\LaravelCloudSdk\Data\WebsocketClusters\WebsocketClusterData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateWebsocketClusterRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(private CreateWebsocketClusterData $data) {}

    public function resolveEndpoint(): string
    {
        return '/websocket-servers';
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
