<?php

namespace Redberry\LaravelCloudSdk\Requests\Domains;

use Redberry\LaravelCloudSdk\Data\Domains\DomainData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetDomainRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $domainId) {}

    public function resolveEndpoint(): string
    {
        return "/domains/{$this->domainId}";
    }

    protected function defaultQuery(): array
    {
        return ['include' => 'environment'];
    }

    public function createDtoFromResponse(Response $response): DomainData
    {
        return JsonApiHydrator::hydrateOne(
            DomainData::class,
            $response->json('data'),
            $response->json('included') ?? [],
        );
    }
}
