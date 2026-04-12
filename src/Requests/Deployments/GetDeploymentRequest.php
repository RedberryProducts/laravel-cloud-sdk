<?php

namespace Redberry\LaravelCloudSdk\Requests\Deployments;

use Redberry\LaravelCloudSdk\Data\Deployments\DeploymentData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetDeploymentRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $deploymentId) {}

    public function resolveEndpoint(): string
    {
        return "/deployments/{$this->deploymentId}";
    }

    protected function defaultQuery(): array
    {
        return ['include' => 'environment,initiator'];
    }

    public function createDtoFromResponse(Response $response): DeploymentData
    {
        return JsonApiHydrator::hydrateOne(
            DeploymentData::class,
            $response->json('data'),
            $response->json('included') ?? [],
        );
    }
}
