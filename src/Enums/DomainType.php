<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum DomainType: string
{
    case Root = 'root';
    case Www = 'www';
    case Wildcard = 'wildcard';
}
