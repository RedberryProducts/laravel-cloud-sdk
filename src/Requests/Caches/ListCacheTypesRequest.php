<?php

namespace Redberry\LaravelCloudSdk\Requests\Caches;

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Data\Caches\CacheTypeData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class ListCacheTypesRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/caches/types';
    }

    /**
     * @return Collection<int, CacheTypeData>
     */
    public function createDtoFromResponse(Response $response): Collection
    {
        return collect($response->json('data'))
            ->map(fn (array $item) => CacheTypeData::fromResponse($item));
    }
}
