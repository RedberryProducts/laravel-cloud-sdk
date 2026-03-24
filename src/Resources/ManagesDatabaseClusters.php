<?php

namespace Redberry\LaravelCloudSdk\Resources;

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\AwsRdsConfigData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\CreateDatabaseClusterData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseClusterData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\LaravelMysqlConfigData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\NeonConfigData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\UpdateDatabaseClusterData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\DatabaseType;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\CreateDatabaseClusterRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\GetDatabaseClusterRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\ListDatabaseClustersRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\ListDatabaseTypesRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\UpdateDatabaseClusterRequest;
use Spatie\LaravelData\Optional;

trait ManagesDatabaseClusters
{
    /**
     * @return Collection<int, DatabaseClusterData>
     */
    public function databaseClusters(): Collection
    {
        return $this->connector->send(new ListDatabaseClustersRequest)->dtoOrFail();
    }

    public function databaseCluster(string $id): DatabaseClusterData
    {
        return $this->connector->send(new GetDatabaseClusterRequest($id))->dtoOrFail();
    }

    public function createDatabaseCluster(
        string $name,
        string|DatabaseType $type,
        string|CloudRegion $region,
        NeonConfigData|LaravelMysqlConfigData|AwsRdsConfigData $config,
        int|Optional $clusterId = new Optional,
    ): DatabaseClusterData {
        return $this->createDatabaseClusterWith(new CreateDatabaseClusterData(
            name: $name,
            type: $type,
            region: $region,
            config: $config,
            clusterId: $clusterId,
        ));
    }

    public function createDatabaseClusterWith(CreateDatabaseClusterData $data): DatabaseClusterData
    {
        return $this->connector->send(new CreateDatabaseClusterRequest($data))->dtoOrFail();
    }

    public function updateDatabaseCluster(
        string $id,
        NeonConfigData|LaravelMysqlConfigData|AwsRdsConfigData $config,
    ): DatabaseClusterData {
        return $this->updateDatabaseClusterWith($id, new UpdateDatabaseClusterData(
            config: $config,
        ));
    }

    public function updateDatabaseClusterWith(string $id, UpdateDatabaseClusterData $data): DatabaseClusterData
    {
        return $this->connector->send(new UpdateDatabaseClusterRequest($id, $data))->dtoOrFail();
    }

    public function databaseTypes(): Collection
    {
        return $this->connector->send(new ListDatabaseTypesRequest)->dtoOrFail();
    }
}
