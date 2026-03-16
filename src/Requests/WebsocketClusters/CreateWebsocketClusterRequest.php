<?php

namespace App\Http\Integrations\LaravelCloud\Requests\WebsocketClusters;

use App\Data\LaravelCloud\WebsocketClusters\CreateWebsocketClusterData;
use App\Data\LaravelCloud\WebsocketClusters\WebsocketClusterData;
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
        $data = $response->json('data');

        return WebsocketClusterData::fromResponse($data['attributes'], $data['id']);
    }
}
