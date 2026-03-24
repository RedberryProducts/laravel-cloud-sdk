<?php

namespace Redberry\LaravelCloudSdk\Requests\WebsocketApplications;

use Redberry\LaravelCloudSdk\Data\WebsocketApplications\WebsocketApplicationData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetWebsocketApplicationRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $applicationId) {}

    public function resolveEndpoint(): string
    {
        return "/websocket-applications/{$this->applicationId}";
    }

    public function createDtoFromResponse(Response $response): WebsocketApplicationData
    {
        $data = $response->json('data');

        return WebsocketApplicationData::fromResponse($data['attributes'], $data['id']);
    }
}
