<?php

namespace Redberry\LaravelCloudSdk\Resources;

use Illuminate\Support\LazyCollection;
use Redberry\LaravelCloudSdk\Data\WebsocketClusters\CreateWebsocketClusterData;
use Redberry\LaravelCloudSdk\Data\WebsocketClusters\UpdateWebsocketClusterData;
use Redberry\LaravelCloudSdk\Data\WebsocketClusters\WebsocketClusterData;
use Redberry\LaravelCloudSdk\Data\WebsocketClusters\WebsocketClusterMetricsData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\MetricPeriod;
use Redberry\LaravelCloudSdk\Enums\WebsocketMaxConnections;
use Redberry\LaravelCloudSdk\Enums\WebsocketServerType;
use Redberry\LaravelCloudSdk\Requests\WebsocketClusters\CreateWebsocketClusterRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketClusters\DeleteWebsocketClusterRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketClusters\GetWebsocketClusterMetricsRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketClusters\GetWebsocketClusterRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketClusters\ListWebsocketClustersRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketClusters\UpdateWebsocketClusterRequest;
use Spatie\LaravelData\Optional;

trait ManagesWebsocketClusters
{
    /**
     * @return LazyCollection<int, WebsocketClusterData>
     */
    public function websocketClusters(): LazyCollection
    {
        return $this->connector->paginate(new ListWebsocketClustersRequest)->collect();
    }

    public function websocketCluster(string $id): WebsocketClusterData
    {
        return $this->connector->send(new GetWebsocketClusterRequest($id))->dtoOrFail();
    }

    public function createWebsocketCluster(
        string $name,
        string|WebsocketServerType $type,
        string|CloudRegion $region,
        string|WebsocketMaxConnections $maxConnections,
    ): WebsocketClusterData {
        return $this->createWebsocketClusterWith(new CreateWebsocketClusterData(
            name: $name,
            type: $type,
            region: $region,
            maxConnections: $maxConnections,
        ));
    }

    public function createWebsocketClusterWith(CreateWebsocketClusterData $data): WebsocketClusterData
    {
        return $this->connector->send(new CreateWebsocketClusterRequest($data))->dtoOrFail();
    }

    public function updateWebsocketCluster(
        string $id,
        string|Optional $name = new Optional,
        string|WebsocketMaxConnections|Optional $maxConnections = new Optional,
    ): WebsocketClusterData {
        return $this->updateWebsocketClusterWith($id, new UpdateWebsocketClusterData(
            name: $name,
            maxConnections: $maxConnections,
        ));
    }

    public function updateWebsocketClusterWith(string $id, UpdateWebsocketClusterData $data): WebsocketClusterData
    {
        return $this->connector->send(new UpdateWebsocketClusterRequest($id, $data))->dtoOrFail();
    }

    public function websocketClusterMetrics(string $id, string|MetricPeriod|null $period = null): WebsocketClusterMetricsData
    {
        return $this->connector->send(new GetWebsocketClusterMetricsRequest($id, $period))->dtoOrFail();
    }

    public function deleteWebsocketCluster(string $id): void
    {
        $this->connector->send(new DeleteWebsocketClusterRequest($id))->throw();
    }
}
