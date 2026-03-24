<?php

namespace App\Data\LaravelCloud\Applications;

use Spatie\LaravelData\Data;

class ApplicationRepositoryData extends Data
{
    public function __construct(
        public string $fullName,
        public string $defaultBranch,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            fullName: $attributes['full_name'],
            defaultBranch: $attributes['default_branch'],
        );
    }
}
