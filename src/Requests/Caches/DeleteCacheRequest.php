<?php

namespace Redberry\LaravelCloudSdk\Requests\Caches;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteCacheRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(private string $cacheId) {}

    public function resolveEndpoint(): string
    {
        return "/caches/{$this->cacheId}";
    }
}
