<?php

namespace Redberry\LaravelCloudSdk\Requests\Deployments;

use Redberry\LaravelCloudSdk\Data\Deployments\DeploymentData;
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

    public function createDtoFromResponse(Response $response): DeploymentData
    {
        $data = $response->json('data');

        return DeploymentData::fromResponse($data['attributes'], $data['id']);
    }
}
