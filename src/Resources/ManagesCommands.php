<?php

namespace Redberry\LaravelCloudSdk\Resources;

use Illuminate\Support\LazyCollection;
use Redberry\LaravelCloudSdk\Data\Commands\CommandData;
use Redberry\LaravelCloudSdk\Data\Commands\RunCommandData;
use Redberry\LaravelCloudSdk\Requests\Commands\GetCommandRequest;
use Redberry\LaravelCloudSdk\Requests\Commands\ListCommandsRequest;
use Redberry\LaravelCloudSdk\Requests\Commands\RunCommandRequest;

trait ManagesCommands
{
    /**
     * @return LazyCollection<int, CommandData>
     */
    public function commands(string $environmentId): LazyCollection
    {
        return $this->connector->paginate(new ListCommandsRequest($environmentId))->collect();
    }

    public function command(string $id): CommandData
    {
        return $this->connector->send(new GetCommandRequest($id))->dtoOrFail();
    }

    public function runCommand(string $environmentId, string $command): CommandData
    {
        return $this->runCommandWith($environmentId, new RunCommandData(command: $command));
    }

    public function runCommandWith(string $environmentId, RunCommandData $data): CommandData
    {
        return $this->connector->send(new RunCommandRequest($environmentId, $data))->dtoOrFail();
    }
}
