<?php

namespace Redberry\LaravelCloudSdk\Requests\Environments;

use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetEnvironmentRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $environmentId) {}

    public function resolveEndpoint(): string
    {
        return "/environments/{$this->environmentId}";
    }

    protected function defaultQuery(): array
    {
        return ['include' => 'application,branch,deployments,currentDeployment,primaryDomain,instances,database,cache,buckets,websocketApplication'];
    }

    public function createDtoFromResponse(Response $response): EnvironmentData
    {
        return JsonApiHydrator::hydrateOne(
            EnvironmentData::class,
            $response->json('data'),
            $response->json('included') ?? [],
        );
    }
}
