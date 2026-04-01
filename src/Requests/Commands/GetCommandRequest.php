<?php

namespace Redberry\LaravelCloudSdk\Requests\Commands;

use Redberry\LaravelCloudSdk\Data\Commands\CommandData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetCommandRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $commandId) {}

    public function resolveEndpoint(): string
    {
        return "/commands/{$this->commandId}";
    }

    public function createDtoFromResponse(Response $response): CommandData
    {
        $data = $response->json('data');

        return CommandData::fromResponse($data['attributes'], $data['id']);
    }
}
