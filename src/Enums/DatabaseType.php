<?php

namespace App\Enums\LaravelCloud;

enum DatabaseType: string
{
    case LARAVEL_MYSQL_84 = 'laravel_mysql_84';
    case LARAVEL_MYSQL_8 = 'laravel_mysql_8';
    case AWS_RDS_MYSQL_8 = 'aws_rds_mysql_8';
    case AWS_RDS_POSTGRES_18 = 'aws_rds_postgres_18';
    case NEON_SERVERLESS_POSTGRES_18 = 'neon_serverless_postgres_18';
    case NEON_SERVERLESS_POSTGRES_17 = 'neon_serverless_postgres_17';
    case NEON_SERVERLESS_POSTGRES_16 = 'neon_serverless_postgres_16';
}
