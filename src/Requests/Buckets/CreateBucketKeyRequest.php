<?php

namespace App\Http\Integrations\LaravelCloud\Requests\Buckets;

use App\Data\LaravelCloud\Buckets\BucketKeyData;
use App\Data\LaravelCloud\Buckets\CreateBucketKeyData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateBucketKeyRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        private string $bucketId,
        private CreateBucketKeyData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/buckets/{$this->bucketId}/keys";
    }

    protected function defaultBody(): array
    {
        return $this->data->toArray();
    }

    public function createDtoFromResponse(Response $response): BucketKeyData
    {
        $data = $response->json('data');

        return BucketKeyData::fromResponse($data['attributes'], $data['id']);
    }
}
