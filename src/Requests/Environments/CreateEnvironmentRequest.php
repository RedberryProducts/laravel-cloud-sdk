<?php

namespace App\Http\Integrations\LaravelCloud\Requests\Environments;

use App\Data\LaravelCloud\Environments\CreateEnvironmentData;
use App\Data\LaravelCloud\Environments\EnvironmentData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateEnvironmentRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        private string $applicationId,
        private CreateEnvironmentData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/applications/{$this->applicationId}/environments";
    }

    protected function defaultBody(): array
    {
        return $this->data->toArray();
    }

    public function createDtoFromResponse(Response $response): EnvironmentData
    {
        $data = $response->json('data');

        return EnvironmentData::fromResponse($data['attributes'], $data['id']);
    }
}
