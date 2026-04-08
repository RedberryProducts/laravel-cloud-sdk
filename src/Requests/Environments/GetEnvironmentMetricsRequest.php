<?php

namespace Redberry\LaravelCloudSdk\Requests\Environments;

use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentMetricsData;
use Redberry\LaravelCloudSdk\Enums\MetricPeriod;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetEnvironmentMetricsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private string $environmentId,
        private string|MetricPeriod|null $period = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/environments/{$this->environmentId}/metrics";
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

    public function createDtoFromResponse(Response $response): EnvironmentMetricsData
    {
        return EnvironmentMetricsData::fromResponse(
            $response->json('data'),
            $response->json('meta'),
        );
    }
}
