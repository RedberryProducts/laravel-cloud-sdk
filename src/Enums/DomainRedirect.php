<?php

namespace App\Enums\LaravelCloud;

enum DomainRedirect: string
{
    case ROOT_TO_WWW = 'root_to_www';
    case WWW_TO_ROOT = 'www_to_root';
}
