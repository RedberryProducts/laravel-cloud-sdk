<?php

namespace App\Enums\LaravelCloud;

enum DatabaseDriver: string
{
    case MYSQL = 'mysql';
    case PGSQL = 'pgsql';
}
