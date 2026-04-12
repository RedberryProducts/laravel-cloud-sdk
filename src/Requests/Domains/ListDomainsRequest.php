<?php

namespace Redberry\LaravelCloudSdk\Requests\Domains;

use Redberry\LaravelCloudSdk\Data\Domains\DomainData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class ListDomainsRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(private string $environmentId) {}

    public function resolveEndpoint(): string
    {
        return "/environments/{$this->environmentId}/domains";
    }

    protected function defaultQuery(): array
    {
        return ['include' => 'environment'];
    }

    /**
     * @return DomainData[]
     */
    public function createDtoFromResponse(Response $response): array
    {
        return JsonApiHydrator::hydrateMany(
            DomainData::class,
            $response->json('data'),
            $response->json('included') ?? [],
        );
    }
}
