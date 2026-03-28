<?php

namespace Redberry\LaravelCloudSdk\Requests\Deployments;

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Data\Deployments\DeploymentData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class ListDeploymentsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $environmentId) {}

    public function resolveEndpoint(): string
    {
        return "/environments/{$this->environmentId}/deployments";
    }

    /**
     * @return Collection<int, DeploymentData>
     */
    public function createDtoFromResponse(Response $response): Collection
    {
        return collect($response->json('data'))
            ->map(fn (array $item) => DeploymentData::fromResponse($item['attributes'], $item['id']));
    }
}
