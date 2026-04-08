<?php

namespace Redberry\LaravelCloudSdk\Requests\Buckets;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteBucketKeyRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(private string $keyId) {}

    public function resolveEndpoint(): string
    {
        return "/bucket-keys/{$this->keyId}";
    }
}
