<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum ClusterType: string
{
    case Applications = 'applications';
    case MysqlDatabases = 'mysql-databases';
    case RdsDatabases = 'rds-databases';
    case RedisCaches = 'redis-caches';
    case ReverbWebsocketServers = 'reverb-websocket-servers';
}
