<?php

namespace Redberry\LaravelCloudSdk\Data\Deployments;

use Spatie\LaravelData\Data;

class DeploymentLogPhaseData extends Data
{
    public function __construct(
        public bool $available,
        /** @var DeploymentLogStepData[] */
        public array $steps,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            available: $attributes['available'],
            steps: array_map(
                fn (array $step) => DeploymentLogStepData::fromResponse($step),
                $attributes['steps'] ?? [],
            ),
        );
    }
}
