<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum DatabaseDriver: string
{
    case Mysql = 'mysql';
    case Pgsql = 'pgsql';
}
