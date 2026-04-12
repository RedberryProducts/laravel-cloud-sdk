<?php

namespace Redberry\LaravelCloudSdk\Requests\Buckets;

use Redberry\LaravelCloudSdk\Data\Buckets\BucketKeyData;
use Redberry\LaravelCloudSdk\Data\Buckets\UpdateBucketKeyData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateBucketKeyRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    public function __construct(
        private string $keyId,
        private UpdateBucketKeyData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/bucket-keys/{$this->keyId}";
    }

    protected function defaultBody(): array
    {
        return $this->data->toArray();
    }

    public function createDtoFromResponse(Response $response): BucketKeyData
    {
        return JsonApiHydrator::hydrateOne(
            BucketKeyData::class,
            $response->json('data'),
            $response->json('included') ?? [],
        );
    }
}
