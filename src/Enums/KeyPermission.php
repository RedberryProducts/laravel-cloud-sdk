<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum KeyPermission: string
{
    case READ_WRITE = 'read_write';
    case READ_ONLY = 'read_only';
}
