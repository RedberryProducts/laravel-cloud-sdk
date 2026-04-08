<?php

namespace Redberry\LaravelCloudSdk\Requests\Buckets;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteBucketRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(private string $bucketId) {}

    public function resolveEndpoint(): string
    {
        return "/buckets/{$this->bucketId}";
    }
}
