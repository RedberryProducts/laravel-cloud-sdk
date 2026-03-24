<?php

namespace App\Http\Integrations\LaravelCloud\Requests\WebsocketApplications;

use App\Data\LaravelCloud\WebsocketApplications\CreateWebsocketApplicationData;
use App\Data\LaravelCloud\WebsocketApplications\WebsocketApplicationData;
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
        $data = $response->json('data');

        return WebsocketApplicationData::fromResponse($data['attributes'], $data['id']);
    }
}
