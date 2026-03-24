<?php

namespace Redberry\LaravelCloudSdk\Requests\Caches;

use Redberry\LaravelCloudSdk\Data\Caches\CacheData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetCacheRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $cacheId) {}

    public function resolveEndpoint(): string
    {
        return "/caches/{$this->cacheId}";
    }

    public function createDtoFromResponse(Response $response): CacheData
    {
        $data = $response->json('data');

        return CacheData::fromResponse($data['attributes'], $data['id']);
    }
}
