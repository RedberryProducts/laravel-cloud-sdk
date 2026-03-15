<?php

namespace App\Http\Integrations\LaravelCloud\Requests\Buckets;

use App\Data\LaravelCloud\Buckets\BucketKeyData;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class ListBucketKeysRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $bucketId) {}

    public function resolveEndpoint(): string
    {
        return "/buckets/{$this->bucketId}/keys";
    }

    /**
     * @return Collection<int, BucketKeyData>
     */
    public function createDtoFromResponse(Response $response): Collection
    {
        return collect($response->json('data'))
            ->map(fn (array $item) => BucketKeyData::fromResponse($item['attributes'], $item['id']));
    }
}
