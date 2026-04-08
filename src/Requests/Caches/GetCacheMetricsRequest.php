<?php

namespace Redberry\LaravelCloudSdk\Requests\Caches;

use Redberry\LaravelCloudSdk\Data\Caches\CacheMetricsData;
use Redberry\LaravelCloudSdk\Enums\MetricPeriod;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetCacheMetricsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private string $cacheId,
        private string|MetricPeriod|null $period = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/caches/{$this->cacheId}/metrics";
    }

    public function defaultQuery(): array
    {
        if ($this->period === null) {
            return [];
        }

        return [
            'period' => $this->period instanceof MetricPeriod ? $this->period->value : $this->period,
        ];
    }

    public function createDtoFromResponse(Response $response): CacheMetricsData
    {
        return CacheMetricsData::fromResponse(
            $response->json('data'),
            $response->json('meta'),
        );
    }
}
