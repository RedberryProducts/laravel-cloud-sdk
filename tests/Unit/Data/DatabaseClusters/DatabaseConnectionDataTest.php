<?php

use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseConnectionData;
use Redberry\LaravelCloudSdk\Enums\DatabaseDriver;
use Redberry\LaravelCloudSdk\Enums\DatabaseProtocol;

it('can be constructed with all parameters', function () {
    $data = new DatabaseConnectionData(
        hostname: 'db.example.com',
        port: 5432,
        protocol: DatabaseProtocol::Postgres,
        driver: DatabaseDriver::Pgsql,
        username: 'admin',
        password: 'secret',
    );

    expect($data->hostname)->toBe('db.example.com');
    expect($data->port)->toBe(5432);
    expect($data->protocol)->toBe(DatabaseProtocol::Postgres);
    expect($data->driver)->toBe(DatabaseDriver::Pgsql);
    expect($data->username)->toBe('admin');
    expect($data->password)->toBe('secret');
});

it('can be created from API response data', function () {
    $responseData = [
        'hostname' => 'db.example.com',
        'port' => 3306,
        'protocol' => 'mysql',
        'driver' => 'mysql',
        'username' => 'root',
        'password' => 'password123',
    ];

    $data = DatabaseConnectionData::fromResponse($responseData);

    expect($data)->toBeInstanceOf(DatabaseConnectionData::class);
    expect($data->hostname)->toBe('db.example.com');
    expect($data->port)->toBe(3306);
    expect($data->protocol)->toBe(DatabaseProtocol::Mysql);
    expect($data->driver)->toBe(DatabaseDriver::Mysql);
    expect($data->username)->toBe('root');
    expect($data->password)->toBe('password123');
});
