<?php

namespace Redberry\LaravelCloudSdk\Resources;

use Illuminate\Support\LazyCollection;
use Redberry\LaravelCloudSdk\Data\WebsocketApplications\CreateWebsocketApplicationData;
use Redberry\LaravelCloudSdk\Data\WebsocketApplications\UpdateWebsocketApplicationData;
use Redberry\LaravelCloudSdk\Data\WebsocketApplications\WebsocketApplicationData;
use Redberry\LaravelCloudSdk\Requests\WebsocketApplications\CreateWebsocketApplicationRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketApplications\DeleteWebsocketApplicationRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketApplications\GetWebsocketApplicationRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketApplications\ListWebsocketApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\WebsocketApplications\UpdateWebsocketApplicationRequest;
use Spatie\LaravelData\Optional;

trait ManagesWebsocketApplications
{
    /**
     * @return LazyCollection<int, WebsocketApplicationData>
     */
    public function websocketApplications(string $clusterId): LazyCollection
    {
        return $this->connector->paginate(new ListWebsocketApplicationsRequest($clusterId))->collect();
    }

    public function websocketApplication(string $id): WebsocketApplicationData
    {
        return $this->connector->send(new GetWebsocketApplicationRequest($id))->dtoOrFail();
    }

    public function createWebsocketApplication(
        string $clusterId,
        string $name,
        int|Optional $pingInterval = new Optional,
        int|Optional $activityTimeout = new Optional,
        array|null|Optional $allowedOrigins = new Optional,
    ): WebsocketApplicationData {
        return $this->createWebsocketApplicationWith($clusterId, new CreateWebsocketApplicationData(
            name: $name,
            pingInterval: $pingInterval,
            activityTimeout: $activityTimeout,
            allowedOrigins: $allowedOrigins,
        ));
    }

    public function createWebsocketApplicationWith(string $clusterId, CreateWebsocketApplicationData $data): WebsocketApplicationData
    {
        return $this->connector->send(new CreateWebsocketApplicationRequest($clusterId, $data))->dtoOrFail();
    }

    public function updateWebsocketApplication(
        string $id,
        string|Optional $name = new Optional,
        int|Optional $pingInterval = new Optional,
        int|Optional $activityTimeout = new Optional,
        array|null|Optional $allowedOrigins = new Optional,
    ): WebsocketApplicationData {
        return $this->updateWebsocketApplicationWith($id, new UpdateWebsocketApplicationData(
            name: $name,
            pingInterval: $pingInterval,
            activityTimeout: $activityTimeout,
            allowedOrigins: $allowedOrigins,
        ));
    }

    public function updateWebsocketApplicationWith(string $id, UpdateWebsocketApplicationData $data): WebsocketApplicationData
    {
        return $this->connector->send(new UpdateWebsocketApplicationRequest($id, $data))->dtoOrFail();
    }

    public function deleteWebsocketApplication(string $id): void
    {
        $this->connector->send(new DeleteWebsocketApplicationRequest($id))->throw();
    }
}
