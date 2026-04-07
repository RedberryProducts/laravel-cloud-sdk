<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum NeonServerlessPostgresComputeUnit: string
{
    case CU_0_25 = '0.25';
    case CU_0_5 = '0.5';
    case CU_1 = '1';
    case CU_2 = '2';
    case CU_4 = '4';
    case CU_8 = '8';
    case CU_10 = '10';
}
