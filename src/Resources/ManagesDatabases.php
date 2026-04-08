<?php

namespace Redberry\LaravelCloudSdk\Resources;

use Illuminate\Support\LazyCollection;
use Redberry\LaravelCloudSdk\Data\Databases\CreateDatabaseData;
use Redberry\LaravelCloudSdk\Data\Databases\DatabaseData;
use Redberry\LaravelCloudSdk\Requests\Databases\CreateDatabaseRequest;
use Redberry\LaravelCloudSdk\Requests\Databases\DeleteDatabaseRequest;
use Redberry\LaravelCloudSdk\Requests\Databases\GetDatabaseRequest;
use Redberry\LaravelCloudSdk\Requests\Databases\ListDatabasesRequest;

trait ManagesDatabases
{
    /**
     * @return LazyCollection<int, DatabaseData>
     */
    public function databases(string $clusterId): LazyCollection
    {
        return $this->connector->paginate(new ListDatabasesRequest($clusterId))->collect();
    }

    public function database(string $clusterId, string $databaseId): DatabaseData
    {
        return $this->connector->send(new GetDatabaseRequest($clusterId, $databaseId))->dtoOrFail();
    }

    public function createDatabase(string $clusterId, string $name): DatabaseData
    {
        return $this->createDatabaseWith($clusterId, new CreateDatabaseData(name: $name));
    }

    public function createDatabaseWith(string $clusterId, CreateDatabaseData $data): DatabaseData
    {
        return $this->connector->send(new CreateDatabaseRequest($clusterId, $data))->dtoOrFail();
    }

    public function deleteDatabase(string $clusterId, string $databaseId): void
    {
        $this->connector->send(new DeleteDatabaseRequest($clusterId, $databaseId))->throw();
    }
}
