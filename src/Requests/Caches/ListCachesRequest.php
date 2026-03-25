<?php

namespace Redberry\LaravelCloudSdk\Requests\Caches;

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Data\Caches\CacheData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class ListCachesRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/caches';
    }

    /**
     * @return Collection<int, CacheData>
     */
    public function createDtoFromResponse(Response $response): Collection
    {
        return collect($response->json('data'))
            ->map(fn (array $item) => CacheData::fromResponse($item['attributes'], $item['id']));
    }
}
