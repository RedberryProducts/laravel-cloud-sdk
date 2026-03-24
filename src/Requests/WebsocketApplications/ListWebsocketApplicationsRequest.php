<?php

namespace App\Http\Integrations\LaravelCloud\Requests\WebsocketApplications;

use App\Data\LaravelCloud\WebsocketApplications\WebsocketApplicationData;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class ListWebsocketApplicationsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $clusterId) {}

    public function resolveEndpoint(): string
    {
        return "/websocket-servers/{$this->clusterId}/applications";
    }

    /**
     * @return Collection<int, WebsocketApplicationData>
     */
    public function createDtoFromResponse(Response $response): Collection
    {
        return collect($response->json('data'))
            ->map(fn (array $item) => WebsocketApplicationData::fromResponse($item['attributes'], $item['id']));
    }
}
