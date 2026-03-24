<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum DatabaseDriver: string
{
    case MYSQL = 'mysql';
    case PGSQL = 'pgsql';
}
