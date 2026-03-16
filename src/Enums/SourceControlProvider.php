<?php

namespace App\Enums\LaravelCloud;

enum SourceControlProvider: string
{
    case GITHUB = 'github';
    case GITLAB = 'gitlab';
    case BITBUCKET = 'bitbucket';
}
