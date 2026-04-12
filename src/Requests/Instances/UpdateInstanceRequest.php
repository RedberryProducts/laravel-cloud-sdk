<?php

namespace Redberry\LaravelCloudSdk\Requests\Instances;

use Redberry\LaravelCloudSdk\Data\Instances\InstanceData;
use Redberry\LaravelCloudSdk\Data\Instances\UpdateInstanceData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateInstanceRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    public function __construct(
        private string $instanceId,
        private UpdateInstanceData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/instances/{$this->instanceId}";
    }

    protected function defaultBody(): array
    {
        return $this->data->toArray();
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
