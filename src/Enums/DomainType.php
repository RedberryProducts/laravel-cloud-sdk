<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum DomainType: string
{
    case ROOT = 'root';
    case WWW = 'www';
    case WILDCARD = 'wildcard';
}
