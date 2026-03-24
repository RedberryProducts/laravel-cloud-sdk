<?php

namespace App\Http\Integrations\LaravelCloud\Requests\Instances;

use App\Data\LaravelCloud\Instances\InstanceData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetInstanceRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $instanceId) {}

    public function resolveEndpoint(): string
    {
        return "/instances/{$this->instanceId}";
    }

    public function createDtoFromResponse(Response $response): InstanceData
    {
        $data = $response->json('data');

        return InstanceData::fromResponse($data['attributes'], $data['id']);
    }
}
