<?php

namespace Redberry\LaravelCloudSdk\Requests\Deployments;

use Redberry\LaravelCloudSdk\Data\Deployments\DeploymentData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class CreateDeploymentRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(private string $environmentId) {}

    public function resolveEndpoint(): string
    {
        return "/environments/{$this->environmentId}/deployments";
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
