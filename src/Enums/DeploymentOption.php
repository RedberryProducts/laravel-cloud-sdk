<?php

namespace App\Enums\LaravelCloud;

enum DeploymentOption: string
{
    case SINGLE_AZ = 'single-az';
    case SINGLE_AZ_WITH_READ_REPLICAS = 'single-az-with-read-replicas';
    case MULTI_AZ = 'multi-az';
}
