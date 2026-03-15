<?php

namespace App\Http\Integrations\LaravelCloud\Requests\Buckets;

use App\Data\LaravelCloud\Buckets\BucketData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetBucketRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $bucketId) {}

    public function resolveEndpoint(): string
    {
        return "/buckets/{$this->bucketId}";
    }

    public function createDtoFromResponse(Response $response): BucketData
    {
        $data = $response->json('data');

        return BucketData::fromResponse($data['attributes'], $data['id']);
    }
}
