<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum KeyPermission: string
{
    case ReadWrite = 'read_write';
    case ReadOnly = 'read_only';
}
