<?php

namespace App\Http\Integrations\LaravelCloud\Requests\WebsocketApplications;

use App\Data\LaravelCloud\WebsocketApplications\UpdateWebsocketApplicationData;
use App\Data\LaravelCloud\WebsocketApplications\WebsocketApplicationData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateWebsocketApplicationRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    public function __construct(
        private string $applicationId,
        private UpdateWebsocketApplicationData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/websocket-applications/{$this->applicationId}";
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
