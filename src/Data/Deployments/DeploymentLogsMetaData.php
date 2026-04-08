<?php

namespace Redberry\LaravelCloudSdk\Data\Deployments;

use Redberry\LaravelCloudSdk\Enums\DeploymentStatus;
use Spatie\LaravelData\Data;

class DeploymentLogsMetaData extends Data
{
    public function __construct(
        public string|DeploymentStatus $deploymentStatus,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            deploymentStatus: DeploymentStatus::tryFrom($attributes['deployment_status']) ?? $attributes['deployment_status'],
        );
    }
}
