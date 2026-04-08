<?php

namespace Redberry\LaravelCloudSdk\Data\Deployments;

use Spatie\LaravelData\Data;

class DeploymentLogStepData extends Data
{
    public function __construct(
        public string $step,
        public string $status,
        public string $description,
        public ?string $output,
        public ?int $durationMs,
        public ?string $time,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            step: $attributes['step'],
            status: $attributes['status'],
            description: $attributes['description'],
            output: $attributes['output'] ?? null,
            durationMs: $attributes['duration_ms'] ?? null,
            time: $attributes['time'] ?? null,
        );
    }
}
