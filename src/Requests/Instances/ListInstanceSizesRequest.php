<?php

namespace Redberry\LaravelCloudSdk\Requests\Instances;

use Redberry\LaravelCloudSdk\Data\Instances\InstanceSizeData;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class ListInstanceSizesRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/instances/sizes';
    }

    public function createDtoFromResponse(Response $response): Collection
    {
        return collect($response->json('data.general'))
            ->map(fn (array $item) => InstanceSizeData::fromResponse($item, $item['name']));
    }
}
