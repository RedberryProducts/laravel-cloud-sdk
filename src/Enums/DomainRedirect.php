<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum DomainRedirect: string
{
    case RootToWww = 'root_to_www';
    case WwwToRoot = 'www_to_root';
}
