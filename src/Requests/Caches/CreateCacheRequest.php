<?php

namespace App\Http\Integrations\LaravelCloud\Requests\Caches;

use App\Data\LaravelCloud\Caches\CacheData;
use App\Data\LaravelCloud\Caches\CreateCacheData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateCacheRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(private CreateCacheData $data) {}

    public function resolveEndpoint(): string
    {
        return '/caches';
    }

    protected function defaultBody(): array
    {
        return $this->data->toArray();
    }

    public function createDtoFromResponse(Response $response): CacheData
    {
        $data = $response->json('data');

        return CacheData::fromResponse($data['attributes'], $data['id']);
    }
}
