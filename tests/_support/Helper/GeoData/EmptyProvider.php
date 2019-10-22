<?php

namespace Helper\GeoData;

use Chocofamily\GeoData\Providers\AbstractGeoProvider;
use Chocofamily\GeoData\DTO\GeoDTO;

class EmptyProvider extends AbstractGeoProvider
{
    public function requestData(string $ip): bool
    {
        $this->geoData = '';

        return $this->isAvailable();
    }

    /**
     * @return string
     */
    protected function getServiceLink(): string
    {
        return '';
    }

    /**
     * @return bool
     */
    protected function containsError(): bool
    {
        return false;
    }

    /**
     * @return GeoDTO
     */
    public function getDTO(): GeoDTO
    {
        return GeoDTO::fromArray([]);
    }
}
