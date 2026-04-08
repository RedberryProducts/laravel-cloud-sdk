<?php

namespace Redberry\LaravelCloudSdk\Requests\Deployments;

use Redberry\LaravelCloudSdk\Data\Deployments\DeploymentLogsData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetDeploymentLogsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $deploymentId) {}

    public function resolveEndpoint(): string
    {
        return "/deployments/{$this->deploymentId}/logs";
    }

    public function createDtoFromResponse(Response $response): DeploymentLogsData
    {
        return DeploymentLogsData::fromResponse(
            $response->json('data'),
            $response->json('meta'),
        );
    }
}
