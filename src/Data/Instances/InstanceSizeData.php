<?php

namespace App\Data\LaravelCloud\Instances;

use Spatie\LaravelData\Data;

class InstanceSizeData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $label,
        public string $description,
        public string $cpuType,
        public string $computeClass,
        public int $cpuCount,
        public int $memoryMib,
    ) {}

    public static function fromResponse(array $attributes, string $id): self
    {
        return new self(
            id: $id,
            name: $attributes['name'],
            label: $attributes['label'],
            description: $attributes['description'],
            cpuType: $attributes['cpu_type'],
            computeClass: $attributes['compute_class'],
            cpuCount: $attributes['cpu_count'],
            memoryMib: $attributes['memory_mib'],
        );
    }
}
