<?php

namespace Redberry\LaravelCloudSdk\Requests\Applications;

use Redberry\LaravelCloudSdk\Data\Applications\ApplicationData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetApplicationRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $applicationId) {}

    public function resolveEndpoint(): string
    {
        return "/applications/{$this->applicationId}";
    }

    protected function defaultQuery(): array
    {
        return ['include' => 'organization,environments,defaultEnvironment'];
    }

    public function createDtoFromResponse(Response $response): ApplicationData
    {
        return JsonApiHydrator::hydrateOne(
            ApplicationData::class,
            $response->json('data'),
            $response->json('included') ?? [],
        );
    }
}
