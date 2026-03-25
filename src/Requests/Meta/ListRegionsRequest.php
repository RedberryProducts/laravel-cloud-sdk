<?php

namespace Redberry\LaravelCloudSdk\Requests\Meta;

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Data\Meta\RegionData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class ListRegionsRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/meta/regions';
    }

    /**
     * @return Collection<int, RegionData>
     */
    public function createDtoFromResponse(Response $response): Collection
    {
        return collect($response->json('data'))
            ->map(fn (array $item) => RegionData::fromResponse($item));
    }
}
