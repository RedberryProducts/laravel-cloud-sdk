<?php

namespace App\Enums\LaravelCloud;

enum DatabaseProtocol: string
{
    case MYSQL = 'mysql';
    case POSTGRES = 'postgres';
}
