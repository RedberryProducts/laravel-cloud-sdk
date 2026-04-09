<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum SourceControlProvider: string
{
    case Github = 'github';
    case Gitlab = 'gitlab';
    case Bitbucket = 'bitbucket';
}
