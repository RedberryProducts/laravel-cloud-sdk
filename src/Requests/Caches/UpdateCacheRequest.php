<?php

namespace Redberry\LaravelCloudSdk\Requests\Caches;

use Redberry\LaravelCloudSdk\Data\Caches\CacheData;
use Redberry\LaravelCloudSdk\Data\Caches\UpdateCacheData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateCacheRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    public function __construct(
        private string $cacheId,
        private UpdateCacheData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/caches/{$this->cacheId}";
    }

    protected function defaultBody(): array
    {
        return $this->data->toArray();
    }

    public function createDtoFromResponse(Response $response): CacheData
    {
        $data = $response->json('data');

        return CacheData::fromResponse($data['attributes'], $data['id']);
    }
}
