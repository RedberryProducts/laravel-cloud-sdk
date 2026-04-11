<?php

namespace Redberry\LaravelCloudSdk\Requests\Applications;

use Redberry\LaravelCloudSdk\Data\Applications\ApplicationData;
use Redberry\LaravelCloudSdk\Data\Applications\CreateApplicationData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateApplicationRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(private CreateApplicationData $data) {}

    public function resolveEndpoint(): string
    {
        return '/applications';
    }

    protected function defaultBody(): array
    {
        return $this->data->toArray();
    }

    public function createDtoFromResponse(Response $response): ApplicationData
    {
        return JsonApiHydrator::hydrateOne(
            ApplicationData::class,
            $response->json('data'),
            $response->json('included') ?? [],
        );
    }
}
