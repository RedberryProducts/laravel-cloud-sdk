<?php

namespace Redberry\LaravelCloudSdk\Requests\Caches;

use Redberry\LaravelCloudSdk\Data\Caches\CacheData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class ListCachesRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/caches';
    }

    /**
     * @return CacheData[]
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            fn (array $item) => CacheData::fromResponse($item['attributes'], $item['id']),
            $response->json('data')
        );
    }
}
