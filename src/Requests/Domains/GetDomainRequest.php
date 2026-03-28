<?php

namespace Redberry\LaravelCloudSdk\Requests\Domains;

use Redberry\LaravelCloudSdk\Data\Domains\DomainData;
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

    public function createDtoFromResponse(Response $response): DomainData
    {
        $data = $response->json('data');

        return DomainData::fromResponse($data['attributes'], $data['id']);
    }
}
