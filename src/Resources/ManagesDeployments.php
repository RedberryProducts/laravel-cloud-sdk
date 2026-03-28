<?php

namespace Redberry\LaravelCloudSdk\Resources;

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Data\Deployments\DeploymentData;
use Redberry\LaravelCloudSdk\Requests\Deployments\CreateDeploymentRequest;
use Redberry\LaravelCloudSdk\Requests\Deployments\GetDeploymentRequest;
use Redberry\LaravelCloudSdk\Requests\Deployments\ListDeploymentsRequest;

trait ManagesDeployments
{
    /**
     * @return Collection<int, DeploymentData>
     */
    public function deployments(string $environmentId): Collection
    {
        return $this->connector->send(new ListDeploymentsRequest($environmentId))->dtoOrFail();
    }

    public function deployment(string $id): DeploymentData
    {
        return $this->connector->send(new GetDeploymentRequest($id))->dtoOrFail();
    }

    public function deploy(string $environmentId): DeploymentData
    {
        return $this->connector->send(new CreateDeploymentRequest($environmentId))->dtoOrFail();
    }
}
