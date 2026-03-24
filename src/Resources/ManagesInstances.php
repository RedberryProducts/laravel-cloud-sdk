<?php

namespace Redberry\LaravelCloudSdk\Resources;

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Data\Instances\CreateInstanceData;
use Redberry\LaravelCloudSdk\Data\Instances\InstanceData;
use Redberry\LaravelCloudSdk\Data\Instances\UpdateInstanceData;
use Redberry\LaravelCloudSdk\Enums\InstanceScalingType;
use Redberry\LaravelCloudSdk\Enums\InstanceSize;
use Redberry\LaravelCloudSdk\Enums\InstanceType;
use Redberry\LaravelCloudSdk\Requests\Instances\CreateInstanceRequest;
use Redberry\LaravelCloudSdk\Requests\Instances\GetInstanceRequest;
use Redberry\LaravelCloudSdk\Requests\Instances\ListInstanceSizesRequest;
use Redberry\LaravelCloudSdk\Requests\Instances\ListInstancesRequest;
use Redberry\LaravelCloudSdk\Requests\Instances\UpdateInstanceRequest;
use Spatie\LaravelData\Optional;

trait ManagesInstances
{
    /**
     * @return Collection<int, InstanceData>
     */
    public function instances(string $environmentId): Collection
    {
        return $this->connector->send(new ListInstancesRequest($environmentId))->dtoOrFail();
    }

    public function instance(string $id): InstanceData
    {
        return $this->connector->send(new GetInstanceRequest($id))->dtoOrFail();
    }

    public function createInstance(
        string $environmentId,
        string $name,
        string|InstanceType $type,
        string|InstanceSize $size,
        string|InstanceScalingType $scalingType,
        int $maxReplicas,
        int $minReplicas,
        bool|Optional $usesScheduler = new Optional,
        int|null|Optional $scalingCpuThresholdPercentage = new Optional,
        int|null|Optional $scalingMemoryThresholdPercentage = new Optional,
        array|Optional $backgroundProcesses = new Optional,
    ): InstanceData {
        return $this->createInstanceWith($environmentId, new CreateInstanceData(
            name: $name,
            type: $type,
            size: $size,
            scalingType: $scalingType,
            maxReplicas: $maxReplicas,
            minReplicas: $minReplicas,
            usesScheduler: $usesScheduler,
            scalingCpuThresholdPercentage: $scalingCpuThresholdPercentage,
            scalingMemoryThresholdPercentage: $scalingMemoryThresholdPercentage,
            backgroundProcesses: $backgroundProcesses,
        ));
    }

    public function createInstanceWith(string $environmentId, CreateInstanceData $data): InstanceData
    {
        return $this->connector->send(new CreateInstanceRequest($environmentId, $data))->dtoOrFail();
    }

    public function updateInstance(
        string $id,
        string|Optional $name = new Optional,
        string|InstanceSize|Optional $size = new Optional,
        string|InstanceScalingType|Optional $scalingType = new Optional,
        int|Optional $maxReplicas = new Optional,
        int|Optional $minReplicas = new Optional,
        bool|Optional $usesSleepMode = new Optional,
        int|Optional $sleepTimeout = new Optional,
        bool|Optional $usesScheduler = new Optional,
        bool|Optional $usesOctane = new Optional,
        bool|Optional $usesInertiaSsr = new Optional,
        int|null|Optional $scalingCpuThresholdPercentage = new Optional,
        int|null|Optional $scalingMemoryThresholdPercentage = new Optional,
    ): InstanceData {
        return $this->updateInstanceWith($id, new UpdateInstanceData(
            name: $name,
            size: $size,
            scalingType: $scalingType,
            maxReplicas: $maxReplicas,
            minReplicas: $minReplicas,
            usesSleepMode: $usesSleepMode,
            sleepTimeout: $sleepTimeout,
            usesScheduler: $usesScheduler,
            usesOctane: $usesOctane,
            usesInertiaSsr: $usesInertiaSsr,
            scalingCpuThresholdPercentage: $scalingCpuThresholdPercentage,
            scalingMemoryThresholdPercentage: $scalingMemoryThresholdPercentage,
        ));
    }

    public function updateInstanceWith(string $id, UpdateInstanceData $data): InstanceData
    {
        return $this->connector->send(new UpdateInstanceRequest($id, $data))->dtoOrFail();
    }

    public function instanceSizes(): Collection
    {
        return $this->connector->send(new ListInstanceSizesRequest)->dtoOrFail();
    }
}
