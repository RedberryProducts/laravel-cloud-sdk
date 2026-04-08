<?php

namespace Redberry\LaravelCloudSdk\Requests\Environments;

use Redberry\LaravelCloudSdk\Data\Deployments\DeploymentData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class StartEnvironmentRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        private string $environmentId,
        private ?bool $redeploy = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/environments/{$this->environmentId}/start";
    }

    protected function defaultBody(): array
    {
        if ($this->redeploy === null) {
            return [];
        }

        return ['redeploy' => $this->redeploy];
    }

    public function createDtoFromResponse(Response $response): DeploymentData
    {
        $data = $response->json('data');

        return DeploymentData::fromResponse($data['attributes'], $data['id']);
    }
}
