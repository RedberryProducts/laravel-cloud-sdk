<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum DatabaseType: string
{
    case LaravelMysql84 = 'laravel_mysql_84';
    case LaravelMysql8 = 'laravel_mysql_8';
    case AwsRdsMysql8 = 'aws_rds_mysql_8';
    case AwsRdsPostgres18 = 'aws_rds_postgres_18';
    case NeonServerlessPostgres18 = 'neon_serverless_postgres_18';
    case NeonServerlessPostgres17 = 'neon_serverless_postgres_17';
    case NeonServerlessPostgres16 = 'neon_serverless_postgres_16';
}
