<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum DomainVerificationMethod: string
{
    case PreVerification = 'pre_verification';
    case RealTime = 'real_time';
}
