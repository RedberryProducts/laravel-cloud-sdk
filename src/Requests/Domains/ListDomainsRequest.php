<?php

namespace App\Http\Integrations\LaravelCloud\Requests\Domains;

use App\Data\LaravelCloud\Domains\DomainData;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class ListDomainsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $environmentId) {}

    public function resolveEndpoint(): string
    {
        return "/environments/{$this->environmentId}/domains";
    }

    public function createDtoFromResponse(Response $response): Collection
    {
        return collect($response->json('data'))
            ->map(fn (array $item) => DomainData::fromResponse($item['attributes'], $item['id']));
    }
}
