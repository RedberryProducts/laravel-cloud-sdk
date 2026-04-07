<?php

namespace Redberry\LaravelCloudSdk\Resources;

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\AwsRdsConfigData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\CreateDatabaseClusterData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\CreateDatabaseSnapshotData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseClusterData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseSnapshotData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\LaravelMysqlConfigData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\NeonServerlessPostgresConfigData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\RestoreDatabaseClusterData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\UpdateDatabaseClusterData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\DatabaseType;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\CreateDatabaseClusterRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\CreateDatabaseSnapshotRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\DeleteDatabaseClusterRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\DeleteDatabaseSnapshotRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\GetDatabaseClusterRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\GetDatabaseSnapshotRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\ListDatabaseClustersRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\ListDatabaseSnapshotsRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\ListDatabaseTypesRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\RestoreDatabaseClusterRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\UpdateDatabaseClusterRequest;
use Spatie\LaravelData\Optional;

trait ManagesDatabaseClusters
{
    /**
     * @return LazyCollection<int, DatabaseClusterData>
     */
    public function databaseClusters(): LazyCollection
    {
        return $this->connector->paginate(new ListDatabaseClustersRequest)->collect();
    }

    public function databaseCluster(string $id): DatabaseClusterData
    {
        return $this->connector->send(new GetDatabaseClusterRequest($id))->dtoOrFail();
    }

    public function createDatabaseCluster(
        string $name,
        string|DatabaseType $type,
        string|CloudRegion $region,
        NeonServerlessPostgresConfigData|LaravelMysqlConfigData|AwsRdsConfigData $config,
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
        NeonServerlessPostgresConfigData|LaravelMysqlConfigData|AwsRdsConfigData $config,
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

    public function deleteDatabaseCluster(string $id): void
    {
        $this->connector->send(new DeleteDatabaseClusterRequest($id))->throw();
    }

    public function createDatabaseSnapshot(string $databaseClusterId, string $name, ?string $description = null): DatabaseSnapshotData
    {
        return $this->createDatabaseSnapshotWith($databaseClusterId, new CreateDatabaseSnapshotData(
            name: $name,
            description: $description,
        ));
    }

    public function createDatabaseSnapshotWith(string $databaseClusterId, CreateDatabaseSnapshotData $data): DatabaseSnapshotData
    {
        return $this->connector->send(new CreateDatabaseSnapshotRequest($databaseClusterId, $data))->dtoOrFail();
    }

    /**
     * @return LazyCollection<int, DatabaseSnapshotData>
     */
    public function databaseSnapshots(string $databaseClusterId): LazyCollection
    {
        return $this->connector->paginate(new ListDatabaseSnapshotsRequest($databaseClusterId))->collect();
    }

    public function databaseSnapshot(string $snapshotId): DatabaseSnapshotData
    {
        return $this->connector->send(new GetDatabaseSnapshotRequest($snapshotId))->dtoOrFail();
    }

    public function restoreDatabaseCluster(
        string $databaseClusterId,
        string $name,
        ?string $restoreTime = null,
        ?string $databaseSnapshotId = null,
    ): DatabaseClusterData {
        return $this->restoreDatabaseClusterWith($databaseClusterId, new RestoreDatabaseClusterData(
            name: $name,
            restoreTime: $restoreTime,
            databaseSnapshotId: $databaseSnapshotId,
        ));
    }

    public function restoreDatabaseClusterWith(string $databaseClusterId, RestoreDatabaseClusterData $data): DatabaseClusterData
    {
        return $this->connector->send(new RestoreDatabaseClusterRequest($databaseClusterId, $data))->dtoOrFail();
    }

    public function deleteDatabaseSnapshot(string $snapshotId): void
    {
        $this->connector->send(new DeleteDatabaseSnapshotRequest($snapshotId))->throw();
    }
}
