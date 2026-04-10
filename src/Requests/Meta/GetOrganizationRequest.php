<?php

namespace Redberry\LaravelCloudSdk\Requests\Meta;

use Redberry\LaravelCloudSdk\Data\Meta\OrganizationData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetOrganizationRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/meta/organization';
    }

    public function createDtoFromResponse(Response $response): OrganizationData
    {
        $data = $response->json('data');

        return OrganizationData::fromResponse($data['attributes'], $data['id']);
    }
}
