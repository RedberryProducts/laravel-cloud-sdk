<?php

namespace App\Data\LaravelCloud\Environments;

use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapOutputName(SnakeCaseMapper::class)]
class FilesystemKeyData extends Data
{
    public function __construct(
        public string $id,
        public string $disk,
        public bool $isDefaultDisk,
    ) {}
}
