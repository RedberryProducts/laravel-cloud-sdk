<?php

namespace App\Http\Integrations\LaravelCloud\Requests\Buckets;

use App\Data\LaravelCloud\Buckets\BucketData;
use App\Data\LaravelCloud\Buckets\UpdateBucketData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateBucketRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    public function __construct(
        private string $bucketId,
        private UpdateBucketData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/buckets/{$this->bucketId}";
    }

    protected function defaultBody(): array
    {
        return $this->data->toArray();
    }

    public function createDtoFromResponse(Response $response): BucketData
    {
        $data = $response->json('data');

        return BucketData::fromResponse($data['attributes'], $data['id']);
    }
}
