<?php

namespace App\Http\Integrations\LaravelCloud\Requests\Domains;

use App\Data\LaravelCloud\Domains\DomainData;
use App\Data\LaravelCloud\Domains\UpdateDomainData;
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
        $data = $response->json('data');

        return DomainData::fromResponse($data['attributes'], $data['id']);
    }
}
