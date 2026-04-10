<?php

namespace Redberry\LaravelCloudSdk\Resources;

use Illuminate\Support\LazyCollection;
use Redberry\LaravelCloudSdk\Data\DedicatedClusters\DedicatedClusterData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\ClusterStatus;
use Redberry\LaravelCloudSdk\Enums\ClusterType;
use Redberry\LaravelCloudSdk\Requests\DedicatedClusters\ListDedicatedClustersRequest;

trait ManagesDedicatedClusters
{
    /**
     * @return LazyCollection<int, DedicatedClusterData>
     */
    public function dedicatedClusters(
        string|CloudRegion|null $region = null,
        string|ClusterType|null $type = null,
        string|ClusterStatus|null $status = null,
    ): LazyCollection {
        return $this->connector->paginate(new ListDedicatedClustersRequest($region, $type, $status))->collect();
    }
}
