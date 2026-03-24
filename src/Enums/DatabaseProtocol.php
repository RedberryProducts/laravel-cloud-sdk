<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum DatabaseProtocol: string
{
    case MYSQL = 'mysql';
    case POSTGRES = 'postgres';
}
