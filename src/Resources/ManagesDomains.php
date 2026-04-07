<?php

namespace Redberry\LaravelCloudSdk\Resources;

use Illuminate\Support\LazyCollection;
use Redberry\LaravelCloudSdk\Data\Domains\CreateDomainData;
use Redberry\LaravelCloudSdk\Data\Domains\DomainData;
use Redberry\LaravelCloudSdk\Data\Domains\UpdateDomainData;
use Redberry\LaravelCloudSdk\Enums\DomainCloudflareStrategy;
use Redberry\LaravelCloudSdk\Enums\DomainRedirect;
use Redberry\LaravelCloudSdk\Enums\DomainVerificationMethod;
use Redberry\LaravelCloudSdk\Requests\Domains\CreateDomainRequest;
use Redberry\LaravelCloudSdk\Requests\Domains\DeleteDomainRequest;
use Redberry\LaravelCloudSdk\Requests\Domains\GetDomainRequest;
use Redberry\LaravelCloudSdk\Requests\Domains\ListDomainsRequest;
use Redberry\LaravelCloudSdk\Requests\Domains\UpdateDomainRequest;
use Redberry\LaravelCloudSdk\Requests\Domains\VerifyDomainRequest;
use Spatie\LaravelData\Optional;

trait ManagesDomains
{
    /**
     * @return LazyCollection<int, DomainData>
     */
    public function domains(string $environmentId): LazyCollection
    {
        return $this->connector->paginate(new ListDomainsRequest($environmentId))->collect();
    }

    public function domain(string $id): DomainData
    {
        return $this->connector->send(new GetDomainRequest($id))->dtoOrFail();
    }

    public function createDomain(
        string $environmentId,
        string $name,
        string|DomainRedirect $wwwRedirect,
        string|DomainVerificationMethod $verificationMethod,
        string|DomainCloudflareStrategy|Optional $cloudflareStrategy = new Optional,
        bool|null|Optional $wildcardEnabled = new Optional,
        bool|null|Optional $allowDowntime = new Optional,
    ): DomainData {
        return $this->createDomainWith($environmentId, new CreateDomainData(
            name: $name,
            wwwRedirect: $wwwRedirect,
            verificationMethod: $verificationMethod,
            cloudflareStrategy: $cloudflareStrategy,
            wildcardEnabled: $wildcardEnabled,
            allowDowntime: $allowDowntime,
        ));
    }

    public function createDomainWith(string $environmentId, CreateDomainData $data): DomainData
    {
        return $this->connector->send(new CreateDomainRequest($environmentId, $data))->dtoOrFail();
    }

    public function updateDomain(
        string $id,
        string|DomainVerificationMethod $verificationMethod,
    ): DomainData {
        return $this->updateDomainWith($id, new UpdateDomainData(
            verificationMethod: $verificationMethod,
        ));
    }

    public function updateDomainWith(string $id, UpdateDomainData $data): DomainData
    {
        return $this->connector->send(new UpdateDomainRequest($id, $data))->dtoOrFail();
    }

    public function verifyDomain(string $id): DomainData
    {
        return $this->connector->send(new VerifyDomainRequest($id))->dtoOrFail();
    }

    public function deleteDomain(string $id): void
    {
        $this->connector->send(new DeleteDomainRequest($id))->throw();
    }
}
