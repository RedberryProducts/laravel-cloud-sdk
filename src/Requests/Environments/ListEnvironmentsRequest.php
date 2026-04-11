<?php

namespace Redberry\LaravelCloudSdk\Requests\Environments;

use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class ListEnvironmentsRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(private string $applicationId) {}

    public function resolveEndpoint(): string
    {
        return "/applications/{$this->applicationId}/environments";
    }

    protected function defaultQuery(): array
    {
        return ['include' => 'application,branch,deployments,currentDeployment,primaryDomain,instances,database,cache,buckets,websocketApplication'];
    }

    /**
     * @return EnvironmentData[]
     */
    public function createDtoFromResponse(Response $response): array
    {
        return JsonApiHydrator::hydrateMany(
            EnvironmentData::class,
            $response->json('data'),
            $response->json('included') ?? [],
        );
    }
}
