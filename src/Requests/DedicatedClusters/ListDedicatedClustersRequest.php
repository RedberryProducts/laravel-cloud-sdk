<?php

namespace Redberry\LaravelCloudSdk\Requests\DedicatedClusters;

use Redberry\LaravelCloudSdk\Data\DedicatedClusters\DedicatedClusterData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\ClusterStatus;
use Redberry\LaravelCloudSdk\Enums\ClusterType;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class ListDedicatedClustersRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private string|CloudRegion|null $region = null,
        private string|ClusterType|null $type = null,
        private string|ClusterStatus|null $status = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/dedicated-clusters';
    }

    public function defaultQuery(): array
    {
        $query = [];

        if ($this->region !== null) {
            $query['filter[region]'] = $this->region instanceof CloudRegion ? $this->region->value : $this->region;
        }

        if ($this->type !== null) {
            $query['filter[type]'] = $this->type instanceof ClusterType ? $this->type->value : $this->type;
        }

        if ($this->status !== null) {
            $query['filter[status]'] = $this->status instanceof ClusterStatus ? $this->status->value : $this->status;
        }

        return $query;
    }

    /**
     * @return DedicatedClusterData[]
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            fn (array $item) => DedicatedClusterData::fromResponse($item['attributes'], $item['id']),
            $response->json('data')
        );
    }
}
