<?php

namespace App\Http\Integrations\LaravelCloud\Requests\Buckets;

use App\Data\LaravelCloud\Buckets\BucketKeyData;
use App\Data\LaravelCloud\Buckets\UpdateBucketKeyData;
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
        $data = $response->json('data');

        return BucketKeyData::fromResponse($data['attributes'], $data['id']);
    }
}
