<?php

namespace App\Http\Integrations\LaravelCloud\Requests\Applications;

use App\Data\LaravelCloud\Applications\ApplicationData;
use App\Data\LaravelCloud\Applications\UpdateApplicationData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateApplicationRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    public function __construct(
        private string $applicationId,
        private UpdateApplicationData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/applications/{$this->applicationId}";
    }

    protected function defaultBody(): array
    {
        return $this->data->toArray();
    }

    public function createDtoFromResponse(Response $response): ApplicationData
    {
        $data = $response->json('data');

        return ApplicationData::fromResponse($data['attributes'], $data['id']);
    }
}
