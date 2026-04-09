<?php

namespace Redberry\LaravelCloudSdk\Paginators;

use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\CursorPaginator;

class LaravelCloudCursorPaginator extends CursorPaginator
{
    protected function isLastPage(Response $response): bool
    {
        $cursor = $response->json('meta.cursor');

        return $cursor === null || $cursor === '';
    }

    protected function getNextCursor(Response $response): int|string
    {
        return $response->json('meta.cursor') ?? '';
    }

    protected function getPageItems(Response $response, Request $request): array
    {
        return $response->dtoOrFail();
    }
}
