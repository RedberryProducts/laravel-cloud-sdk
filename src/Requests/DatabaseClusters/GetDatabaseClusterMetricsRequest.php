<?php

namespace Redberry\LaravelCloudSdk\Requests\DatabaseClusters;

use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseClusterMetricsData;
use Redberry\LaravelCloudSdk\Enums\MetricPeriod;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetDatabaseClusterMetricsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private string $databaseClusterId,
        private string|MetricPeriod|null $period = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/databases/clusters/{$this->databaseClusterId}/metrics";
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

    public function createDtoFromResponse(Response $response): DatabaseClusterMetricsData
    {
        return DatabaseClusterMetricsData::fromResponse(
            $response->json('data'),
            $response->json('meta'),
        );
    }
}
