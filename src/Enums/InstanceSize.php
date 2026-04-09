<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum InstanceSize: string
{
    case FlexC1vcpu256mb = 'flex.c-1vcpu-256mb';
    case FlexG1vcpu512mb = 'flex.g-1vcpu-512mb';
    case FlexM1vcpu1gb = 'flex.m-1vcpu-1gb';
    case FlexC2vcpu512mb = 'flex.c-2vcpu-512mb';
    case FlexG2vcpu1gb = 'flex.g-2vcpu-1gb';
    case FlexM2vcpu2gb = 'flex.m-2vcpu-2gb';
    case FlexC4vcpu1gb = 'flex.c-4vcpu-1gb';
    case FlexG4vcpu2gb = 'flex.g-4vcpu-2gb';
    case FlexM4vcpu4gb = 'flex.m-4vcpu-4gb';
    case FlexC8vcpu2gb = 'flex.c-8vcpu-2gb';
    case FlexG8vcpu4gb = 'flex.g-8vcpu-4gb';
    case FlexM8vcpu8gb = 'flex.m-8vcpu-8gb';

    case ProC1vcpu1gb = 'pro.c-1vcpu-1gb';
    case ProG1vcpu2gb = 'pro.g-1vcpu-2gb';
    case ProM1vcpu4gb = 'pro.m-1vcpu-4gb';
    case ProC2vcpu2gb = 'pro.c-2vcpu-2gb';
    case ProG2vcpu4gb = 'pro.g-2vcpu-4gb';
    case ProM2vcpu8gb = 'pro.m-2vcpu-8gb';
    case ProC4vcpu4gb = 'pro.c-4vcpu-4gb';
    case ProG4vcpu8gb = 'pro.g-4vcpu-8gb';
    case ProM4vcpu16gb = 'pro.m-4vcpu-16gb';
    case ProC8vcpu8gb = 'pro.c-8vcpu-8gb';
    case ProG8vcpu16gb = 'pro.g-8vcpu-16gb';
    case ProM8vcpu32gb = 'pro.m-8vcpu-32gb';

    case DedicatedC1vcpu2gb = 'dedicated.c-1vcpu-2gb';
    case DedicatedG1vcpu4gb = 'dedicated.g-1vcpu-4gb';
    case DedicatedM1vcpu8gb = 'dedicated.m-1vcpu-8gb';
    case DedicatedC2vcpu4gb = 'dedicated.c-2vcpu-4gb';
    case DedicatedG2vcpu8gb = 'dedicated.g-2vcpu-8gb';
    case DedicatedM2vcpu16gb = 'dedicated.m-2vcpu-16gb';
    case DedicatedC4vcpu8gb = 'dedicated.c-4vcpu-8gb';
    case DedicatedG4vcpu16gb = 'dedicated.g-4vcpu-16gb';
    case DedicatedM4vcpu32gb = 'dedicated.m-4vcpu-32gb';
    case DedicatedC8vcpu16gb = 'dedicated.c-8vcpu-16gb';
    case DedicatedG8vcpu32gb = 'dedicated.g-8vcpu-32gb';
    case DedicatedM8vcpu64gb = 'dedicated.m-8vcpu-64gb';
}
