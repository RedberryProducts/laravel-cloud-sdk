<?php

namespace Redberry\LaravelCloudSdk\Requests\Instances;

use Redberry\LaravelCloudSdk\Data\Instances\InstanceData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
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

    protected function defaultQuery(): array
    {
        return ['include' => 'environment,backgroundProcesses'];
    }

    public function createDtoFromResponse(Response $response): InstanceData
    {
        return JsonApiHydrator::hydrateOne(
            InstanceData::class,
            $response->json('data'),
            $response->json('included') ?? [],
        );
    }
}
