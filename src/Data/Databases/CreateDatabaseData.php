<?php

namespace Redberry\LaravelCloudSdk\Data\Databases;

use Spatie\LaravelData\Data;

class CreateDatabaseData extends Data
{
    public function __construct(
        public string $name,
    ) {}
}
