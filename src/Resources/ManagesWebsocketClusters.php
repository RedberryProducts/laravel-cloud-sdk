<?php

namespace Redberry\LaravelCloudSdk\Resources;

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Data\WebsocketClusters\CreateWebsocketClusterData;
use Redberry\LaravelCloudSdk\Data\WebsocketClusters\UpdateWebsocketClusterData;
use Redberry\LaravelCloudSdk\Data\WebsocketClusters\WebsocketClusterData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\WebsocketMaxConnections;
use Redberry\LaravelCloudSdk\Enums\WebsocketServerType;
use Redberry\LaravelCloudSdk\Requests\WebsocketClusters\CreateWebsocketClusterRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketClusters\GetWebsocketClusterRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketClusters\ListWebsocketClustersRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketClusters\UpdateWebsocketClusterRequest;
use Spatie\LaravelData\Optional;

trait ManagesWebsocketClusters
{
    /**
     * @return Collection<int, WebsocketClusterData>
     */
    public function websocketClusters(): Collection
    {
        return $this->connector->send(new ListWebsocketClustersRequest)->dtoOrFail();
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
}
