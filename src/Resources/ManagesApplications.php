<?php

namespace Redberry\LaravelCloudSdk\Resources;

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Data\Applications\ApplicationData;
use Redberry\LaravelCloudSdk\Data\Applications\CreateApplicationData;
use Redberry\LaravelCloudSdk\Data\Applications\UpdateApplicationData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\SourceControlProvider;
use Redberry\LaravelCloudSdk\Requests\Applications\CreateApplicationRequest;
use Redberry\LaravelCloudSdk\Requests\Applications\GetApplicationRequest;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\Applications\UpdateApplicationRequest;
use Spatie\LaravelData\Optional;

trait ManagesApplications
{
    /**
     * @return Collection<int, ApplicationData>
     */
    public function applications(): Collection
    {
        return $this->connector->send(new ListApplicationsRequest)->dtoOrFail();
    }

    public function application(string $id): ApplicationData
    {
        return $this->connector->send(new GetApplicationRequest($id))->dtoOrFail();
    }

    public function createApplication(
        string $repository,
        string $name,
        string|CloudRegion $region,
        string|SourceControlProvider $sourceControlProviderType,
        string|null|Optional $clusterId = new Optional,
    ): ApplicationData {
        return $this->createApplicationWith(new CreateApplicationData(
            repository: $repository,
            name: $name,
            region: $region,
            sourceControlProviderType: $sourceControlProviderType,
            clusterId: $clusterId,
        ));
    }

    public function createApplicationWith(CreateApplicationData $data): ApplicationData
    {
        return $this->connector->send(new CreateApplicationRequest($data))->dtoOrFail();
    }

    public function updateApplication(
        string $id,
        string|SourceControlProvider|Optional $sourceControlProviderType = new Optional,
        string|Optional $name = new Optional,
        string|Optional $slug = new Optional,
        string|Optional $defaultEnvironmentId = new Optional,
        string|Optional $repository = new Optional,
        string|null|Optional $slackChannel = new Optional,
    ): ApplicationData {
        return $this->updateApplicationWith($id, new UpdateApplicationData(
            sourceControlProviderType: $sourceControlProviderType,
            name: $name,
            slug: $slug,
            defaultEnvironmentId: $defaultEnvironmentId,
            repository: $repository,
            slackChannel: $slackChannel,
        ));
    }

    public function updateApplicationWith(string $id, UpdateApplicationData $data): ApplicationData
    {
        return $this->connector->send(new UpdateApplicationRequest($id, $data))->dtoOrFail();
    }
}
