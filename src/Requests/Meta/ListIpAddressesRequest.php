<?php

namespace Redberry\LaravelCloudSdk\Requests\Meta;

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Data\Meta\IpAddressData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class ListIpAddressesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private string|CloudRegion|null $region = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/ip';
    }

    public function defaultQuery(): array
    {
        if ($this->region === null) {
            return [];
        }

        return [
            'region' => $this->region instanceof CloudRegion ? $this->region->value : $this->region,
        ];
    }

    /**
     * @return Collection<string, IpAddressData>
     */
    public function createDtoFromResponse(Response $response): Collection
    {
        return collect($response->json())
            ->map(fn (array $data, string $region) => IpAddressData::fromResponse($region, $data));
    }
}
