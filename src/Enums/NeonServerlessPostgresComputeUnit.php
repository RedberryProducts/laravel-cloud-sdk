<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum NeonServerlessPostgresComputeUnit: string
{
    case Cu0_25 = '0.25';
    case Cu0_5 = '0.5';
    case Cu1 = '1';
    case Cu2 = '2';
    case Cu4 = '4';
    case Cu8 = '8';
    case Cu10 = '10';
}
