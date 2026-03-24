<?php

namespace App\Http\Integrations\LaravelCloud\Requests\Domains;

use App\Data\LaravelCloud\Domains\CreateDomainData;
use App\Data\LaravelCloud\Domains\DomainData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateDomainRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        private string $environmentId,
        private CreateDomainData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/environments/{$this->environmentId}/domains";
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
