<?php

namespace App\Enums\LaravelCloud;

enum DomainType: string
{
    case ROOT = 'root';
    case WWW = 'www';
    case WILDCARD = 'wildcard';
}
