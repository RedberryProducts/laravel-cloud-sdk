<?php

namespace Redberry\LaravelCloudSdk\Resources;

use Illuminate\Support\LazyCollection;
use Redberry\LaravelCloudSdk\Data\BackgroundProcesses\BackgroundProcessConfigData;
use Redberry\LaravelCloudSdk\Data\BackgroundProcesses\BackgroundProcessData;
use Redberry\LaravelCloudSdk\Data\BackgroundProcesses\CreateBackgroundProcessData;
use Redberry\LaravelCloudSdk\Data\BackgroundProcesses\UpdateBackgroundProcessData;
use Redberry\LaravelCloudSdk\Enums\DaemonType;
use Redberry\LaravelCloudSdk\Requests\BackgroundProcesses\CreateBackgroundProcessRequest;
use Redberry\LaravelCloudSdk\Requests\BackgroundProcesses\DeleteBackgroundProcessRequest;
use Redberry\LaravelCloudSdk\Requests\BackgroundProcesses\GetBackgroundProcessRequest;
use Redberry\LaravelCloudSdk\Requests\BackgroundProcesses\ListBackgroundProcessesRequest;
use Redberry\LaravelCloudSdk\Requests\BackgroundProcesses\UpdateBackgroundProcessRequest;
use Spatie\LaravelData\Optional;

trait ManagesBackgroundProcesses
{
    /**
     * @return LazyCollection<int, BackgroundProcessData>
     */
    public function backgroundProcesses(string $instanceId): LazyCollection
    {
        return $this->connector->paginate(new ListBackgroundProcessesRequest($instanceId))->collect();
    }

    public function backgroundProcess(string $id): BackgroundProcessData
    {
        return $this->connector->send(new GetBackgroundProcessRequest($id))->dtoOrFail();
    }

    public function createBackgroundProcess(
        string $instanceId,
        string|DaemonType $type,
        int $processes,
        string|null|Optional $command = new Optional,
        BackgroundProcessConfigData|null|Optional $config = new Optional,
    ): BackgroundProcessData {
        return $this->createBackgroundProcessWith(
            $instanceId,
            new CreateBackgroundProcessData(
                type: $type,
                processes: $processes,
                command: $command,
                config: $config,
            )
        );
    }

    public function createBackgroundProcessWith(string $instanceId, CreateBackgroundProcessData $data): BackgroundProcessData
    {
        return $this->connector->send(new CreateBackgroundProcessRequest($instanceId, $data))->dtoOrFail();
    }

    public function updateBackgroundProcess(
        string $id,
        string|DaemonType|Optional $type = new Optional,
        int|Optional $processes = new Optional,
        string|null|Optional $command = new Optional,
        BackgroundProcessConfigData|null|Optional $config = new Optional,
    ): BackgroundProcessData {
        return $this->updateBackgroundProcessWith(
            $id,
            new UpdateBackgroundProcessData(
                type: $type,
                processes: $processes,
                command: $command,
                config: $config,
            )
        );
    }

    public function updateBackgroundProcessWith(string $id, UpdateBackgroundProcessData $data): BackgroundProcessData
    {
        return $this->connector->send(new UpdateBackgroundProcessRequest($id, $data))->dtoOrFail();
    }

    public function deleteBackgroundProcess(string $id): void
    {
        $this->connector->send(new DeleteBackgroundProcessRequest($id))->throw();
    }
}
