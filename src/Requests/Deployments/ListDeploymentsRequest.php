<?php

namespace Redberry\LaravelCloudSdk\Requests\Deployments;

use Redberry\LaravelCloudSdk\Data\Deployments\DeploymentData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class ListDeploymentsRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(private string $environmentId) {}

    public function resolveEndpoint(): string
    {
        return "/environments/{$this->environmentId}/deployments";
    }

    protected function defaultQuery(): array
    {
        return ['include' => 'environment,initiator'];
    }

    /**
     * @return DeploymentData[]
     */
    public function createDtoFromResponse(Response $response): array
    {
        return JsonApiHydrator::hydrateMany(
            DeploymentData::class,
            $response->json('data'),
            $response->json('included') ?? [],
        );
    }
}
