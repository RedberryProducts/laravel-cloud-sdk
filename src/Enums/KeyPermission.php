<?php

namespace App\Enums\LaravelCloud;

enum KeyPermission: string
{
    case READ_WRITE = 'read_write';
    case READ_ONLY = 'read_only';
}
