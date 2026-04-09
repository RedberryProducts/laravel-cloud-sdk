<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum DeploymentOption: string
{
    case SingleAz = 'single-az';
    case SingleAzWithReadReplicas = 'single-az-with-read-replicas';
    case MultiAz = 'multi-az';
}
