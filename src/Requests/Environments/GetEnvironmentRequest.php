<?php

namespace App\Http\Integrations\LaravelCloud\Requests\Environments;

use App\Data\LaravelCloud\Environments\EnvironmentData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetEnvironmentRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $environmentId) {}

    public function resolveEndpoint(): string
    {
        return "/environments/{$this->environmentId}";
    }

    public function createDtoFromResponse(Response $response): EnvironmentData
    {
        $data = $response->json('data');

        return EnvironmentData::fromResponse($data['attributes'], $data['id']);
    }
}
