<?php

namespace Redberry\LaravelCloudSdk\Requests\Domains;

use Redberry\LaravelCloudSdk\Data\Domains\DomainData;
use Redberry\LaravelCloudSdk\Data\Domains\UpdateDomainData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateDomainRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    public function __construct(
        private string $domainId,
        private UpdateDomainData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/domains/{$this->domainId}";
    }

    protected function defaultBody(): array
    {
        return $this->data->toArray();
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
