<?php

namespace App\Http\Integrations\LaravelCloud\Requests\Buckets;

use App\Data\LaravelCloud\Buckets\BucketData;
use App\Data\LaravelCloud\Buckets\CreateBucketData;
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
        $data = $response->json('data');

        return BucketData::fromResponse($data['attributes'], $data['id']);
    }
}
