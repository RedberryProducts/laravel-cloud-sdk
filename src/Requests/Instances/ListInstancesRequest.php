<?php

namespace Redberry\LaravelCloudSdk\Requests\Instances;

use Redberry\LaravelCloudSdk\Data\Instances\InstanceData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class ListInstancesRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(private string $environmentId) {}

    public function resolveEndpoint(): string
    {
        return "/environments/{$this->environmentId}/instances";
    }

    protected function defaultQuery(): array
    {
        return ['include' => 'environment,backgroundProcesses'];
    }

    /**
     * @return InstanceData[]
     */
    public function createDtoFromResponse(Response $response): array
    {
        return JsonApiHydrator::hydrateMany(
            InstanceData::class,
            $response->json('data'),
            $response->json('included') ?? [],
        );
    }
}
