<?php

arch('it will not use debugging functions')
    ->expect(['dd', 'dump', 'ray'])
    ->each->not->toBeUsed();

arch('enums are backed enums')
    ->expect('Redberry\LaravelCloudSdk\Enums')
    ->toBeEnums()
    ->toHaveMethod('from');

arch('data classes extend spatie data')
    ->expect('Redberry\LaravelCloudSdk\Data')
    ->classes()
    ->toExtend('Spatie\LaravelData\Data');

arch('requests extend saloon request')
    ->expect('Redberry\LaravelCloudSdk\Requests')
    ->toExtend('Saloon\Http\Request');

arch('sdk does not depend on illuminate http')
    ->expect('Redberry\LaravelCloudSdk')
    ->not->toUse('Illuminate\Http');
