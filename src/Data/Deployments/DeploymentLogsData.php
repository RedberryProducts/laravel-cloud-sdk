<?php

namespace Redberry\LaravelCloudSdk\Data\Deployments;

use Spatie\LaravelData\Data;

class DeploymentLogsData extends Data
{
    public function __construct(
        public DeploymentLogPhaseData $build,
        public DeploymentLogPhaseData $deploy,
        public DeploymentLogsMetaData $meta,
    ) {}

    public static function fromResponse(array $data, array $meta): self
    {
        return new self(
            build: DeploymentLogPhaseData::fromResponse($data['build']),
            deploy: DeploymentLogPhaseData::fromResponse($data['deploy']),
            meta: DeploymentLogsMetaData::fromResponse($meta),
        );
    }
}
