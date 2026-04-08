<?php

namespace Redberry\LaravelCloudSdk\Requests\Environments;

use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class StopEnvironmentRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(
        private string $environmentId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/environments/{$this->environmentId}/stop";
    }

    public function createDtoFromResponse(Response $response): EnvironmentData
    {
        $data = $response->json('data');

        return EnvironmentData::fromResponse($data['attributes'], $data['id']);
    }
}
