<?php

namespace Redberry\LaravelCloudSdk\Requests\Buckets;

use Redberry\LaravelCloudSdk\Data\Buckets\BucketData;
use Redberry\LaravelCloudSdk\Data\Buckets\CreateBucketData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateBucketRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(private CreateBucketData $data) {}

    public function resolveEndpoint(): string
    {
        return '/buckets';
    }

    protected function defaultBody(): array
    {
        return $this->data->toArray();
    }

    public function createDtoFromResponse(Response $response): BucketData
    {
        return JsonApiHydrator::hydrateOne(
            BucketData::class,
            $response->json('data'),
            $response->json('included') ?? [],
        );
    }
}
