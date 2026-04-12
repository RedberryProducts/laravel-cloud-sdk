<?php

namespace Redberry\LaravelCloudSdk\Requests\WebsocketApplications;

use Redberry\LaravelCloudSdk\Data\WebsocketApplications\CreateWebsocketApplicationData;
use Redberry\LaravelCloudSdk\Data\WebsocketApplications\WebsocketApplicationData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateWebsocketApplicationRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        private string $clusterId,
        private CreateWebsocketApplicationData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/websocket-servers/{$this->clusterId}/applications";
    }

    protected function defaultBody(): array
    {
        return $this->data->toArray();
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
