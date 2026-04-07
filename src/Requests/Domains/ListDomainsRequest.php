<?php

namespace Redberry\LaravelCloudSdk\Requests\Domains;

use Redberry\LaravelCloudSdk\Data\Domains\DomainData;
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

    /**
     * @return DomainData[]
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            fn (array $item) => DomainData::fromResponse($item['attributes'], $item['id']),
            $response->json('data')
        );
    }
}
