<?php

namespace Redberry\LaravelCloudSdk\Requests\BackgroundProcesses;

use Redberry\LaravelCloudSdk\Data\BackgroundProcesses\BackgroundProcessData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class ListBackgroundProcessesRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(private string $instanceId) {}

    public function resolveEndpoint(): string
    {
        return "/instances/{$this->instanceId}/background-processes";
    }

    protected function defaultQuery(): array
    {
        return ['include' => 'instance'];
    }

    /**
     * @return BackgroundProcessData[]
     */
    public function createDtoFromResponse(Response $response): array
    {
        return JsonApiHydrator::hydrateMany(
            BackgroundProcessData::class,
            $response->json('data'),
            $response->json('included') ?? [],
        );
    }
}
