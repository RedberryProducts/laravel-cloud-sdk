<?php

namespace Redberry\LaravelCloudSdk\Requests\Buckets;

use Redberry\LaravelCloudSdk\Data\Buckets\BucketKeyData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetBucketKeyRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $keyId) {}

    public function resolveEndpoint(): string
    {
        return "/bucket-keys/{$this->keyId}";
    }

    public function createDtoFromResponse(Response $response): BucketKeyData
    {
        $data = $response->json('data');

        return BucketKeyData::fromResponse($data['attributes'], $data['id']);
    }
}
