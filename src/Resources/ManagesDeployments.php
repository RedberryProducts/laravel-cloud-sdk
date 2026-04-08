<?php

namespace Redberry\LaravelCloudSdk\Resources;

use Illuminate\Support\LazyCollection;
use Redberry\LaravelCloudSdk\Data\Deployments\DeploymentData;
use Redberry\LaravelCloudSdk\Data\Deployments\DeploymentLogsData;
use Redberry\LaravelCloudSdk\Requests\Deployments\CreateDeploymentRequest;
use Redberry\LaravelCloudSdk\Requests\Deployments\GetDeploymentLogsRequest;
use Redberry\LaravelCloudSdk\Requests\Deployments\GetDeploymentRequest;
use Redberry\LaravelCloudSdk\Requests\Deployments\ListDeploymentsRequest;

trait ManagesDeployments
{
    /**
     * @return LazyCollection<int, DeploymentData>
     */
    public function deployments(string $environmentId): LazyCollection
    {
        return $this->connector->paginate(new ListDeploymentsRequest($environmentId))->collect();
    }

    public function deployment(string $id): DeploymentData
    {
        return $this->connector->send(new GetDeploymentRequest($id))->dtoOrFail();
    }

    public function deploy(string $environmentId): DeploymentData
    {
        return $this->connector->send(new CreateDeploymentRequest($environmentId))->dtoOrFail();
    }

    public function deploymentLogs(string $id): DeploymentLogsData
    {
        return $this->connector->send(new GetDeploymentLogsRequest($id))->dtoOrFail();
    }
}
