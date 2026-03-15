<?php

namespace App\Http\Integrations\LaravelCloud\Requests\Caches;

use App\Data\LaravelCloud\Caches\CacheData;
use Illuminate\Support\Collection;
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
